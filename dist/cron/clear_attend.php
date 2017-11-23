<?php
	session_start();

	//Подключение к БД и установка кодировки для кириллицы
	include("../db.php");
	mysqli_close($con);

	//Очистка папки с отчетами
	$files = glob('../reports/*');
	foreach($files as $file){
	  if(is_file($file))
	    unlink($file);
	}	
?>