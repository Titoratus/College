<?php
	$con = mysqli_connect("localhost", "forallne_college", "Jadeij05", "forallne_college") or die("Не удалось подключиться к БД");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");
?>