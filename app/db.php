<?php
	$con = mysqli_connect("localhost", "a0170627", "Jadeij05", "a0170627_data") or die("Не удалось подключиться к БД");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");
?>