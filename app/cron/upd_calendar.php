<?php
	include("../db.php");

	//Очистка таблицы Выходных
	$query = mysqli_query($con, "TRUNCATE TABLE weekends");

	//Пометка выходных дней (Сб и Вс)
	$year = date("Y");
	date("L", mktime(0,0,0,7,7,$year)) ? $leap = 366 : $leap = 365;

	for($i=1; $i <= $leap; $i++){
		if(date('D', mktime(0, 0, 0, 1, $i, $year))== "Sun" or date('D', mktime(0, 0, 0, 1, $i, $year)) == "Sat"){
			$date = date('d', mktime(0, 0, 0, 1, $i, $year));
			$date .= date('m', mktime(0, 0, 0, 1, $i, $year));
			$date .= substr($year, 2);
			$query = mysqli_query($con, "INSERT INTO weekends (`date`) VALUES ('$date')");
		}
		else if(date('M', mktime(0, 0, 0, 1, $i, $year)) == "May" || date('M', mktime(0, 0, 0, 1, $i, $year)) == "Jun" || date('M', mktime(0, 0, 0, 1, $i, $year)) == "Jul"){
			$date = date('d', mktime(0, 0, 0, 1, $i, $year));
			$date .= date('m', mktime(0, 0, 0, 1, $i, $year));
			$date .= substr($year, 2);
			$query = mysqli_query($con, "INSERT INTO weekends (`date`) VALUES ('$date')");	
		}
	}
	mysqli_close($con);
?>