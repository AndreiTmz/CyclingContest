<?php
    if (!isset($link)) {
        include("shared/db_connect.php");
    }
?>

<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo isset($page_title) ? $page_title : ''; ?></title>
    
    <link rel="stylesheet" href="css/styles.css" type="text/css" >
    <link rel="icon" href="images/favicon.ico">
    <script src="js/rollover.js"></script>

    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
</head>

<body<?php if (!empty($body_class)) { echo ' class="' . htmlspecialchars($body_class, ENT_QUOTES, 'UTF-8') . '"'; } ?>>
	<div id="container">
			<div id="header_animation">
				<img class="header_animation" src="images/cycle.gif" width="65px" height="45px">
			</div>
	
			<div id="header">
				<div id="logo">
					<h1>Cycling contest 2017</h1>
				</div>
				
				<div id="menu">
                    <ul>
                        <li <?php if(isset($section) && $section=='Media') echo 'class="active"';?>>
                            <a href="media.php" class="mainNav" id="media" onmouseover="rollOver(1, 'media', 'media_results');" onmouseout="rollOver(0, 'media', 'media_results');">Media</a>
                            <div class="rolloverMenu" id="media_results" style="display:none;" onmouseover="rollOver(1, 'media', 'media_results');" onmouseout="rollOver(0, 'media', 'media_results');">
                                    <a href="photos.php"> Photos </a> <br/ >
                                    <a href="videos.php"> Videos </a> <br />
                                    <a href="routes.php"> Routes </a>
                            </div>
                        </li>
                        <li <?php if(isset($section) && $section=='Results') echo 'class="active"';?>>
                            <a href="results.php">Results</a>
                        </li>
					    <li <?php if(isset($section) && $section=='Competitors') echo 'class="active"';?>>
                            <a href="competitors.php">Competitors</a>
					    </li>
                        <li <?php if(isset($section) && $section=='Categories') echo 'class="active"';?>>
                            <a href="categories.php">Categories</a>
                        </li>
                        <li <?php if(isset($section) && $section=='Registration') echo 'class="active"';?>>
                            <a href="register.php">Register</a>
                        </li>
                        <li <?php if(isset($section) && $section=='Home') echo 'class="active"';?>>
                            <a href="index.php"><span>Home</span></a>
                        </li>
				</ul>
				</div>
			</div>
			<div id="wrapper">