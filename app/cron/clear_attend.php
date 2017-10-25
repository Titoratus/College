<?php
	session_start();

	//Подключение к БД и установка кодировки для кириллицы
	$con = mysqli_connect("localhost", "root", "", "college");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");
	$query = mysqli_query($con, "TRUNCATE TABLE attend");
	mysqli_close($con);

	//Очистка папки с отчетами
	$files = glob('../reports/*');
	foreach($files as $file){
	  if(is_file($file))
	    unlink($file);
	}	
?>