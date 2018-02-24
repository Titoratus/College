<?php
	$con = mysqli_connect("localhost", "root", "", "college") or die("Не удалось подключиться к БД");
	$query = mysqli_query($con, "SET NAMES UTF8");
?>