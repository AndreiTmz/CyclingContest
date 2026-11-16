<?php
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	if (empty($_SESSION['authenticated'])) {
		header('Location: login.php');
		exit;
	}

	include("shared/db_connect.php");
	$page_title = "Admin";
	$section = 'Admin';
	include("shared/header.php"); 
	include("shared/enums.php");
	$escape_html = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
	if (empty($_SESSION['admin_csrf_token'])) {
		$_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
	}
	$csrf_token = $_SESSION['admin_csrf_token'];
	$action_error = '';
	$updated_row_id = null;
	$search_name = is_string($_POST['name'] ?? null) ? trim($_POST['name']) : '';
	$age_filter = is_string($_POST['age_category'] ?? null) ? $_POST['age_category'] : '';
	$gender_filter = is_string($_POST['gender'] ?? null) ? strtoupper($_POST['gender']) : '';
	if (!in_array($age_filter, ['', 'JUNIOR', 'INTERMEDIATE', 'SENIOR'], true)) {
		$age_filter = '';
	}
	if (!in_array($gender_filter, ['', 'M', 'F'], true)) {
		$gender_filter = '';
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['competitor_id'], $_POST['category_id'])) {
		$submitted_token = is_string($_POST['csrf_token'] ?? null) ? $_POST['csrf_token'] : '';
		$competitor_id = filter_var($_POST['competitor_id'], FILTER_VALIDATE_INT);
		$category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
		if (!hash_equals($csrf_token, $submitted_token)) {
			$action_error = 'The request could not be verified. Reload the page and try again.';
		} elseif ($competitor_id === false || $category_id === false || $competitor_id < 1 || $category_id < 1) {
			$action_error = 'Invalid competitor or category.';
		} else {
			$status = null;
			$finish_position = null;
			$set_finish_position = false;
			if (isset($_POST['finish_position'])) {
				$submitted_position = is_string($_POST['finish_position']) ? $_POST['finish_position'] : '';
				if (in_array($submitted_position, ['1', '2', '3'], true)) {
					$status = result_status::FINISHED->name;
					$finish_position = (int)$submitted_position;
					$set_finish_position = true;
				} elseif ($submitted_position === 'not_awarded') {
					$status = result_status::FINISHED->name;
					$set_finish_position = true;
				} else {
					$action_error = 'Invalid finish position.';
				}
			} elseif (isset($_POST['disqualify'])) {
				$status = result_status::DISQUALIFIED->name;
			} elseif (isset($_POST['not_finished'])) {
				$status = result_status::NOT_FINISHED->name;
			}

			if ($status !== null && $set_finish_position) {
				$statement = mysqli_prepare($link, 'UPDATE results SET finish_position = ?, status = ? WHERE competitor_id = ? AND category_id = ?');
				mysqli_stmt_bind_param($statement, 'isii', $finish_position, $status, $competitor_id, $category_id);
				$update_succeeded = mysqli_stmt_execute($statement);
				mysqli_stmt_close($statement);
				if ($update_succeeded) {
					$updated_row_id = 'result-' . $competitor_id . '-' . $category_id;
				}
			} elseif ($status !== null) {
				$statement = mysqli_prepare($link, 'UPDATE results SET status = ?, finish_position = NULL WHERE competitor_id = ? AND category_id = ?');
				mysqli_stmt_bind_param($statement, 'sii', $status, $competitor_id, $category_id);
				$update_succeeded = mysqli_stmt_execute($statement);
				mysqli_stmt_close($statement);
				if ($update_succeeded) {
					$updated_row_id = 'result-' . $competitor_id . '-' . $category_id;
				}
			} elseif ($action_error === '') {
				$action_error = 'Choose a result action.';
			}
		}
	}
?>	

<?php if ($action_error !== '') { ?>
<p class="error"><?php echo $escape_html($action_error); ?></p>
<?php } ?>

<u><i><h1 align="center">Competitors results administration</h1></i></u>

<br />
<?php
	$search_form = [
		'action' => 'admin.php',
		'label' => 'Search competitor',
		'name_field' => 'name',
		'placeholder' => 'search by name',
		'name_value' => $search_name,
		'gender_value' => $gender_filter,
		'age_category_value' => $age_filter,
		'csrf_token' => $csrf_token,
	];
	include __DIR__ . '/shared/search_form.php';
?>
<br />

<?php
			$conditions = [];
			$parameters = [];
			if ($search_name !== '') {
				$conditions[] = "(c.first_name LIKE ? OR c.last_name LIKE ? OR CONCAT(c.first_name, ' ', c.last_name) LIKE ?)";
				$name_pattern = '%' . $search_name . '%';
				array_push($parameters, $name_pattern, $name_pattern, $name_pattern);
			}
			if ($age_filter !== '') {
				$conditions[] = 'r.age_category = ?';
				$parameters[] = $age_filter;
			}
			if ($gender_filter !== '') {
				$conditions[] = 'c.gender = ?';
				$parameters[] = $gender_filter;
			}
			$query = "SELECT c.id AS competitor_id, c.gender, c.first_name, c.last_name,
				r.age_category, cat.name AS category_name, cat.id AS category_id,
				r.finish_position, r.status
				FROM competitors c
				JOIN results r ON c.id = r.competitor_id
				JOIN categories cat ON r.category_id = cat.id";
			if ($conditions) {
				$query .= ' WHERE ' . implode(' AND ', $conditions);
			}
			$query .= ' ORDER BY c.id';
			$statement = mysqli_prepare($link, $query);
			mysqli_stmt_execute($statement, $parameters);
			$result = mysqli_stmt_get_result($statement);
			if (mysqli_num_rows($result) == 0) {
				echo '<div class="empty-state" role="status">No competitors found.</div>';
			} else {
		?>

<table class="data-table" id="competitors-results">
	<tr>
		<th>No.<br /></th>
		<th>Name</th>
		<th>Category</th>
		<th>Age category</th>
		<th>Status</th>
		<th>Place</th>
		<th>Update<br />Result</th>
		<th>Other actions</th>
	</tr>
	
	<?php while($row = mysqli_fetch_assoc($result)){ ?>
		<tr id="result-<?php echo (int)$row['competitor_id']; ?>-<?php echo (int)$row['category_id']; ?>">
			<td> <?php echo (int)$row['competitor_id']; ?> </td>
			<td> <?php echo $escape_html($row['first_name'] . ' ' . $row['last_name']); ?> </td>
			<td> <?php echo $escape_html($row['category_name']); ?> </td>
			<td> <?php echo $escape_html($row['age_category']); ?> </td>
			<td> <?php echo $escape_html(result_status::{$row['status']}->value); ?> </td>
			<td> <?php echo $escape_html($row['finish_position'] ?? 'Not Awarded'); ?> </td>
			
			<td>
				<form action="admin.php" method="post">
					<input type="hidden" name="csrf_token" value="<?php echo $escape_html($csrf_token); ?>">
					<input type="hidden" name="competitor_id" value="<?php echo $row['competitor_id']; ?>">
					<input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>">
					<input type="hidden" name="name" value="<?php echo $escape_html($search_name); ?>">
					<input type="hidden" name="age_category" value="<?php echo $escape_html($age_filter); ?>">
					<input type="hidden" name="gender" value="<?php echo $escape_html($gender_filter); ?>">

					<button type="submit" name="finish_position" value="1" class="changeReward action-place">1st</button>
					<button type="submit" name="finish_position" value="2" class="changeReward action-place">2nd</button>
					<button type="submit" name="finish_position" value="3" class="changeReward action-place">3rd</button>
					<button type="submit" name="finish_position" value="not_awarded" class="changeReward action-status">
						Not Awarded
					</button>
				</form>
			</td>
			<td>
				<form action="admin.php" method="post">
					<input type="hidden" name="csrf_token" value="<?php echo $escape_html($csrf_token); ?>">
					<input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>" />
					<input type="hidden" name="competitor_id" value="<?php echo $row['competitor_id']; ?>" />
					<input type="hidden" name="name" value="<?php echo $escape_html($search_name); ?>">
					<input type="hidden" name="age_category" value="<?php echo $escape_html($age_filter); ?>">
					<input type="hidden" name="gender" value="<?php echo $escape_html($gender_filter); ?>">

					<button type="submit" name="disqualify" value="disqualify" class="changeReward action-danger">Disqualify</button>
					<button type="submit" name="not_finished" value="not_finished" class="changeReward action-status">Not Finished</button>
				</form>
			</td>
		</tr>
			<?php } ?>
	</table>
	<?php 
		}
	?>
<?php if ($updated_row_id !== null) { ?>
<script>
	document.getElementById(<?php echo json_encode($updated_row_id); ?>)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
</script>
<?php } ?>
<?php 
	include("shared/footer.php"); 
?>


