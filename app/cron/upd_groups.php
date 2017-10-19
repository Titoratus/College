<?php
	session_start();
	$con = mysqli_connect("localhost", "root", "", "college");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");
	

	function deleteGroup($group){
		global $con;
		$query2 = mysqli_query($con, "DELETE FROM students WHERE s_group='$group'");
		$query2 = mysqli_query($con, "DELETE FROM curator_group WHERE group_ID='$group'");
		$query2 = mysqli_query($con, "DELETE FROM groups WHERE group_ID = '$group'");
	}	
	//Удаление 4-го курса
	$query = mysqli_query($con, "SELECT * FROM groups WHERE course = '4'");
	while($row = mysqli_fetch_array($query)){
		$group = $row["group_ID"];
		deleteGroup($group);
	}

	//Переход на следующий курс
	for($i = 3; $i >= 1; $i--){
		$query = mysqli_query($con, "SELECT * FROM groups WHERE course = '$i'");
		while($row = mysqli_fetch_array($query)){
			//Название группы - в число
			$old_group = (int)$row["group_ID"];
			//Увеличиваем число (название группы) на 10
			$new_group = $old_group + 10;
			//Добавляем эту группу в groups
			$new_course = $i+1;
			$add = mysqli_query($con, "INSERT INTO groups (`group_ID`, `course`) VALUES ('$new_group', '$new_course')");

			//Изменяем старое название группы на новое
			$upd_group = mysqli_query($con, "UPDATE students SET s_group='$new_group' WHERE s_group='$old_group'");
			$curator = mysqli_query($con, "SELECT * FROM curator_group WHERE group_ID='$old_group'");
			$curator = mysqli_fetch_array($curator);
			$curator = $curator["curator_ID"];
			$upd_group = mysqli_query($con, "UPDATE curator_group SET group_ID='$new_group' WHERE curator_ID='$curator'");

			//Удаляем старую группу
			deleteGroup($old_group);
		}
	}
?>