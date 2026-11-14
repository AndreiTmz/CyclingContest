<?php
	$host = getenv('DB_HOST') ?: 'localhost';
	$user = getenv('DB_USER') ?: 'root';
	$password = getenv('DB_PASSWORD') ?: 'root';
	$db = getenv('DB_NAME') ?: 'cycling_contest';
	$link = mysqli_connect($host, $user, $password, $db);

	if(!$link)
	{	
        die("Connection couldn't be established ".mysqli_connect_error());
	}

	mysqli_set_charset($link, 'utf8mb4');
	
	if(!isset($_SESSION))
	{
		session_start();		
	}
?>