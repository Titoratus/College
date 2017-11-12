<?php
	session_start();
	include("../db.php");
	
	//Удаление 4-го курса
	$query = mysqli_query($con, "SELECT * FROM groups WHERE course = '4'");
	while($row = mysqli_fetch_array($query)){
		$group = $row["group_ID"];
		$delete = mysqli_query($con, "DELETE FROM groups WHERE group_ID='$group'");
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
			$edit = mysqli_query($con, "UPDATE groups SET group_ID='$new_group', course='$new_course' WHERE group_ID='$old_group'");
		}
	}
	mysqli_close($con);
?>