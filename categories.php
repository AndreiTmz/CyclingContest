<?php
    $page_title = "Contest categories";
    $section = "Categories";
    include ("shared/db_connect.php");
    include ("shared/header.php");

    $categories_query = "SELECT * FROM categories";
    $categories_result = mysqli_query($link, $categories_query);
    $picture_alignment = "left";
?>

<h1>About contest categories</h1>
<hr  />

<?php
    while($row = mysqli_fetch_assoc($categories_result))
    {
?>

        <div class="categories">
            <h2 align="center"><i><?php echo $row['name']; ?></i></h2>
            <img src="images/<?php echo $row['image_name']; ?>" 
                align="<?php echo $picture_alignment; ?>" width="200px" height="180px"/>
            <p>
                <?php echo $row['description']; ?>
            </p>
        </div>

<?php 
        $picture_alignment = ($picture_alignment == "left") ? "right" : "left";
    }
?>

<?php
    include ("shared/footer.php");
?>