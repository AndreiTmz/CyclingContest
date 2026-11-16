<?php
    $page_title = "Competitors";
    $section = "Competitors";
    include ("shared/db_connect.php");
    include ("shared/header.php");
    include ("shared/enums.php");

    $escape_html = static fn($value) => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    $search_name = is_string($_POST['competitor_name'] ?? null) ? trim($_POST['competitor_name']) : '';
    $gender_filter = is_string($_POST['gender'] ?? null) ? strtoupper($_POST['gender']) : '';
    $age_filter = is_string($_POST['age_category'] ?? null) ? $_POST['age_category'] : '';
    if (!in_array($gender_filter, ['', 'M', 'F'], true)) {
        $gender_filter = '';
    }
    if (!in_array($age_filter, ['', 'JUNIOR', 'INTERMEDIATE', 'SENIOR'], true)) {
        $age_filter = '';
    }
?>

<h2 align="center">Competitors</h2>
<br />
<?php
    $search_form = [
        'action' => 'competitors.php',
        'label' => 'Search for competitor',
        'name_field' => 'competitor_name',
        'placeholder' => 'Search by name',
        'name_value' => $search_name,
        'gender_value' => $gender_filter,
        'age_category_value' => $age_filter,
    ];
    include __DIR__ . '/shared/search_form.php';
?>
<br />

<?php
    $conditions = [];
    $parameters = [];
    if ($search_name !== '') {
        $conditions[] = "(cmp.first_name LIKE ? OR cmp.last_name LIKE ? OR CONCAT(cmp.first_name, ' ', cmp.last_name) LIKE ?)";
        $name_pattern = '%' . $search_name . '%';
        array_push($parameters, $name_pattern, $name_pattern, $name_pattern);
    }
    if ($gender_filter !== '') {
        $conditions[] = 'cmp.gender = ?';
        $parameters[] = $gender_filter;
    }
    if ($age_filter !== '') {
        $conditions[] = 'res.age_category = ?';
        $parameters[] = $age_filter;
    }
    $query = "SELECT cmp.id, cmp.first_name, cmp.last_name, cmp.gender,
                cmp.date_of_birth, cmp.email, cmp.phone_number, res.age_category,
                GROUP_CONCAT(DISTINCT cat.name SEPARATOR ', ') AS categories_name
        FROM competitors cmp
        JOIN results res ON cmp.id = res.competitor_id
        JOIN categories cat ON res.category_id = cat.id";
    if ($conditions) {
        $query .= ' WHERE ' . implode(' AND ', $conditions);
    }
    $query .= " GROUP BY cmp.id, cmp.first_name, cmp.last_name, cmp.gender,
                cmp.date_of_birth, cmp.email, cmp.phone_number, res.age_category
                ORDER BY cmp.id";

    $statement = mysqli_prepare($link, $query);
    mysqli_stmt_execute($statement, $parameters);
    $result = mysqli_stmt_get_result($statement);

    if (mysqli_num_rows($result) == 0) 
    {
        echo '<div class="empty-state" role="status">No competitors found.</div>';
    }
    else
    {
?>

<table class="data-table">
    <tr>
        <th>No.</th>
        <th>Name</th>
        <th>Gender</th>
        <th>Date of birth</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Categories</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result))
        { 
    ?>
        <tr>
            <td> <?php echo (int)$row['id']; ?> </td>
            <td> <?php echo $escape_html($row['first_name'] . ' ' . $row['last_name']); ?> </td>
            <td> <?php echo $escape_html($row['gender']); ?> </td>
            <td>
                <?php echo $escape_html($row['date_of_birth']); ?><br>
                <?php echo $escape_html($row['age_category'] !== '' ? age_category::{$row['age_category']}->value : 'N/A'); ?>
            </td>
            <td> <?php echo $escape_html($row['email']); ?> </td>
            <td> <?php echo $escape_html($row['phone_number']); ?> </td>
            <td> <?php echo $escape_html($row['categories_name']); ?> </td>
		</tr>
    <?php 
        } 
    ?>
</table>

<?php 
    }
?>

<?php
    include ("shared/footer.php");
?>