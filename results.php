<?php
    $page_title = "Results";
    $section = "Results";
    $body_class = 'results-page';
    include ("shared/db_connect.php");
    include ("shared/header.php");
    include ("shared/enums.php");

    $escape_html = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    $categories_query = "SELECT id, name, image_name FROM categories ORDER BY id";
    $categories_result = mysqli_query($link, $categories_query);
    $requested_category = filter_var($_GET['category'] ?? null, FILTER_VALIDATE_INT);
    $selected_category = $requested_category !== false && $requested_category > 0 ? $requested_category : null;
    $current_category_name = null;

    if ($selected_category) {
        $category_statement = mysqli_prepare($link, 'SELECT name FROM categories WHERE id = ?');
        mysqli_stmt_execute($category_statement, [$selected_category]);
        $category_result = mysqli_stmt_get_result($category_statement);
        $category_row = mysqli_fetch_assoc($category_result);
        if ($category_row) {
            $current_category_name = $category_row['name'];
        } else {
            $selected_category = null;
        }
    }
?>



<?php
    if ($selected_category) {
        $search_name = is_string($_POST['competitor_name'] ?? null) ? trim($_POST['competitor_name']) : '';
        $gender_filter = is_string($_POST['gender'] ?? null) ? strtoupper($_POST['gender']) : '';
        $age_filter = is_string($_POST['age_category'] ?? null) ? $_POST['age_category'] : '';
        if (!in_array($gender_filter, ['', 'M', 'F'], true)) {
            $gender_filter = '';
        }
        if (!in_array($age_filter, ['', 'JUNIOR', 'INTERMEDIATE', 'SENIOR'], true)) {
            $age_filter = '';
        }
        $conditions = ['r.category_id = ?'];
        $parameters = [$selected_category];
        if ($search_name !== '') {
            $conditions[] = "(c.first_name LIKE ? OR c.last_name LIKE ? OR CONCAT(c.first_name, ' ', c.last_name) LIKE ?)";
            $name_pattern = '%' . $search_name . '%';
            array_push($parameters, $name_pattern, $name_pattern, $name_pattern);
        }
        if ($gender_filter !== '') {
            $conditions[] = 'c.gender = ?';
            $parameters[] = $gender_filter;
        }
        if ($age_filter !== '') {
            $conditions[] = 'r.age_category = ?';
            $parameters[] = $age_filter;
        }
        $category_results_query = "SELECT r.competitor_id, r.finish_position, r.status, r.age_category,
            c.first_name, c.last_name
            FROM results r JOIN competitors c ON r.competitor_id = c.id
            WHERE " . implode(' AND ', $conditions) . ' ORDER BY r.finish_position IS NULL, r.finish_position, r.competitor_id';
        $results_statement = mysqli_prepare($link, $category_results_query);
        mysqli_stmt_execute($results_statement, $parameters);
        $category_results = mysqli_stmt_get_result($results_statement);
?>
    <h1 align="center" style="margin-top:45px;">
         <?php echo $escape_html($current_category_name . ' Results'); ?>
    </h1>
    <br />
			
    <?php
        $search_form = [
            'action' => 'results.php?category=' . (int)$selected_category,
            'label' => 'Search for competitor',
            'name_field' => 'competitor_name',
            'placeholder' => 'Search by name',
            'name_value' => $search_name,
            'gender_value' => $gender_filter,
            'age_category_value' => $age_filter,
            'style' => 'margin-left:150px;',
        ];
        include __DIR__ . '/shared/search_form.php';
    ?>
    <br />
    <table border="2px" align="center" width="600px">
        <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Finish position</th>
            <th>Status</th>
            <th>Age category</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($category_results))
            { 
        ?>
            <tr>
                <td> <?php echo (int)$row['competitor_id']; ?> </td>
                <td> <?php echo $escape_html($row['first_name'] . ' ' . $row['last_name']); ?> </td>
                <td> <?php echo $escape_html($row['finish_position'] ?? 'Not Awarded'); ?> </td>
                <td> <?php echo $escape_html(result_status::{$row['status']}->value); ?> </td>
                <td> <?php echo $escape_html($row['age_category']); ?> </td>
            </tr>
        <?php 
            } 
        ?>
    </table>

<?php } else { ?>

    <header class="results-intro">
        <h1>Competition results</h1>
        <p>Choose a category to view the standings.</p>
    </header>

    <div class="results-categories" align="center">
        <?php 
            while($row = mysqli_fetch_assoc($categories_result))
            {
        ?>
                <a href="results.php?category=<?php echo (int)$row['id']; ?>" 
                    class="<?php echo $escape_html($row['name']); ?>">
                <button class="results"
                    style="background-image: url('images/<?php echo rawurlencode($row['image_name']); ?>');">
                        <b><?php echo $escape_html(strtoupper($row['name'])); ?></b>
                </button>
                </a>
        <?php
            }
        ?>
    </div>

<?php
    }
    include ("shared/footer.php");
?>