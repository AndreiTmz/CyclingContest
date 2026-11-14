<?php
    $page_title="Media";
    $section="Media";
    include ("shared/header.php");
?>

    <h1 align="center" style="margin-top:45px;" >Photos and videos</h1>
    <hr style="margin-bottom:50px;"/>

    <a href="photos.php"><button class="media" style="background-image:url(images/MountainBike.jpg);background-attachment:fixed; background-size:50%;"><b>Photos</b></button></a>

    <a href="videos.php"><button class="media" style="background-image:url(images/vidbtn.jpg);background-attachment:fixed; background-size:50%;"><b>Videos</b></button></a>

    <a href="routes.php"><button class="media" style="background-image:url(images/path.jpg);background-attachment:fixed; background-size:100%;"><b>Routes</b></button></a>

<?php
    include ("shared/footer.php");
?>