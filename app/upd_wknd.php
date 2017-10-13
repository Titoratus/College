<?php
	$con = mysqli_connect("localhost", "root", "", "college");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");

	//Получаем значение даты, на которую кликнули
	$date = $_POST["date"];
	//Добавляем в таблицу
	$query = mysqli_query($con, "INSERT INTO weekends (`date`) VALUES ('$date')");
?>