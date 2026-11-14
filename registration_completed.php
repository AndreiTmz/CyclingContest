<?php
	$page_title = "Register";
	$section = "Registration";
	include ("shared/header.php");
    $competitor_id = $_SESSION['registered_competitor_id'] ?? null;
    unset($_SESSION['registered_competitor_id']);
?>

<img src="images/register.jpg" class="register_img" />

<div class="registrationCompleted">
    <?php if ($competitor_id !== null) { ?>
        <h2>Registration Completed</h2>
        <h3>You are competitor number <?php echo (int)$competitor_id; ?></h3>
        <b>Good luck in the competition!!!</b>
    <?php } else { ?>
        <h2>No recent registration found</h2>
        <p>Please complete the registration form first.</p>
    <?php } ?>
</div>

<div class="viewMedia">
    <h3 align="center">Media</h3>
    <ul>
        <li></li>
        <li><h3 align="center"><a href="photos.php"> View photos </a></h3></li>
        <li><h3 align="center"><a href="videos.php"> View videos </a></h3></li>
        <li><h3 align="center"><a href="routes.php"> View routes </a></h3></li>
    </ul>
</div>

<?php
    include ("shared/footer.php");
?>