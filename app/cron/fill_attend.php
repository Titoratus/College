<?php
	session_start();
	$con = mysqli_connect("localhost", "root", "", "college");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");

	$curr_date = date('d').date('m').date('y');
	$query = mysqli_query($con, "SELECT * FROM weekends WHERE date='$curr_date'");
	if(mysqli_num_rows($query) != 0) die("Сегодня выходной");

	$query = mysqli_query($con, "SELECT * FROM curator_group");
	while($row = mysqli_fetch_array($query)){
		$c_group = $row["group_ID"];
		$c_ID = $row["curator_ID"];
		//Находим группу куратора
		$query2 = mysqli_query($con, "SELECT * FROM students WHERE s_group='$c_group'");

		while($row2 = mysqli_fetch_array($query2)){
			$s_ID = $row2['student_ID'];
			$fill = mysqli_query($con, "INSERT INTO attend (`date`, `student_ID`, `curator_ID`, `P`, `U`, `B`) VALUES ('$curr_date', '$s_ID', '$c_ID', '0', '0', '0')");
		}
	}
?>