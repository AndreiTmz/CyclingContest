<?php
    $page_title = "Contest categories";
    $section = "Categories";
    include ("shared/db_connect.php");
    include ("shared/header.php");

    $categories_query = "SELECT * FROM categories";
    $categories_result = mysqli_query($link, $categories_query);
?>

<h1>About contest categories</h1>
<hr  />

<div class="category-grid">
    <?php while ($row = mysqli_fetch_assoc($categories_result)) { ?>
        <article class="category-card">
            <h2><em><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></em></h2>
            <img src="images/<?php echo htmlspecialchars($row['image_name'], ENT_QUOTES, 'UTF-8'); ?>"
                alt="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>" />
            <p><?php echo htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?></p>
        </article>
    <?php } ?>
</div>

<?php
    include ("shared/footer.php");
?>