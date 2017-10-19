<?php
	session_start();
	$con = mysqli_connect("localhost", "root", "", "college");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");

	//-----------ГРУППЫ-----------
	if(isset($_POST["new_group"])){
		$group_ID = $_POST["new_group"];
		$group_course = $_POST["course"];

		//Заносим в groups
		$query = mysqli_query($con, "INSERT INTO groups (`group_ID`, `course`) VALUES ('$group_ID', '$group_course')");
		if(!$query) die("Такая группа уже есть!");	

		//Заносим в curator_group
		$curator_ID = $_SESSION["curator_ID"];
		$query = mysqli_query($con, "INSERT INTO curator_group (`curator_ID`, `group_ID`) VALUES ('$curator_ID', '$group_ID')");

		//Если изменили название группы
		if(isset($_POST["old_group"])){
			$old_group = $_POST["old_group"];
			$query = mysqli_query($con, "UPDATE students SET s_group='$group_ID' WHERE s_group='$old_group'");
			$query = mysqli_query($con, "DELETE FROM curator_group WHERE group_ID='$old_group'");
			$query = mysqli_query($con, "DELETE FROM groups WHERE group_ID = '$old_group'");			
		}

		$query = mysqli_query($con, "SELECT * FROM curator_group WHERE curator_ID='$curator_ID'");
		$group = mysqli_fetch_array($query);
		$group = $group["group_ID"];
	?>
	<div data-group="<?php echo $group_ID; ?>" class="group_name"><?php echo $group_ID; ?><span class="edit_group">edit</span></div>
	<div class="groups_table">
		<table>
			<tr>
				<th>№</th>
				<th>ФИО</th>
			</tr>
			<?php
				$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$group'");

				$s = 0;
				while($row = mysqli_fetch_array($query)){
					$s=$s+1;
					echo "<tr><td>".$s."</td><td>".$row['s_surname']." ".$row['s_name']." ".$row['s_father']."<a class='del_stud' data-group='".$group."' data-student='".$row['student_ID']."'>Удалить</a></td></tr>";
				}
			?>
		</table>

		<form id="add_student" action="" method="POST">
			<input type="text" name="new_s_surname" autocomplete="off" required>
			<input type="text" data-group="<?php echo $group; ?>" name="new_s_name" autocomplete="off" required>
			<input type="text" name="new_s_father" autocomplete="off" required>
			<input type="submit" value="Добавить">
		</form>		
	</div>
<?php
	}

	//Если нажали "Удалить"
	if(isset($_POST['del_group'])){
		$group = $_POST['del_group'];

		$query = mysqli_query($con, "DELETE FROM curator_group WHERE group_ID='$group'");
		$query = mysqli_query($con, "DELETE FROM students WHERE s_group='$group'");
		$query = mysqli_query($con, "DELETE FROM groups WHERE group_ID = '$group'");

		$curator_ID = $_SESSION["curator_ID"];
		$query = mysqli_query($con, "SELECT * FROM curator_group WHERE curator_ID = '$curator_ID'");
		//Если нет групп
		if(mysqli_num_rows($query)==0){
		?>
		<form id="add_group" action="" method="POST">
			<input type="text" name="group_name" placeholder="Название" autocomplete="off" required>
			<input type="text" name="group_course" placeholder="Курс" autocomplete="off" required>
			<input type="submit" value="Создать">
		</form>
		<div class="groups_table"></div>
		<?php
		}
	}

	if(isset($_POST["new_s_name"]) || isset($_POST["del_stud"])){
		//Если добавили нового студента
		if(isset($_POST["new_s_name"])){
			$group = $_POST["selected_group"];
			$s_name = $_POST["new_s_name"];
			$s_surname = $_POST["new_s_surname"];
			$s_father = $_POST["new_s_father"];
			$query = mysqli_query($con, "INSERT INTO students (`student_ID`, `s_name`, `s_surname`, `s_father`, `s_group`) VALUES (NULL, '$s_name', '$s_surname', '$s_father', '$group')");
		}
		//Если нажали удалить студента
		if(isset($_POST["del_stud"])){
			$s_ID = $_POST["del_stud"];
			$query = mysqli_query($con, "SELECT s_group FROM students WHERE student_ID='$s_ID'");
			$group = $_POST["group"];
			//Удаление
			$query = mysqli_query($con, "DELETE FROM students WHERE student_ID='$s_ID'");
		}
?>
	<table>
		<tr>
			<th>№</th>
			<th>ФИО</th>
		</tr>
		<?php
			$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$group'");

			$s = 0;
			while($row = mysqli_fetch_array($query)){
				$s=$s+1;
				echo "<tr><td>".$s."</td><td>".$row['s_surname']." ".$row['s_name']." ".$row['s_father']."<a class='del_stud' data-group='".$group."' data-student='".$row['student_ID']."'>Удалить</a></td></tr>";
			}
		?>
	</table>

	<form id="add_student" action="" method="POST">
		<input type="text" name="new_s_surname" autocomplete="off" required>
		<input type="text" data-group="<?php echo $group; ?>" name="new_s_name" autocomplete="off" required>
		<input type="text" name="new_s_father" autocomplete="off" required>
		<input type="submit" value="Добавить">
	</form>
<?php	} ?>



<?php	
	//-----------ВЫХОДНЫЕ-----------
	//Получаем значение даты, на которую кликнули
	//Добавление
	if(isset($_POST["add_day"])){
		$date = $_POST["add_day"];
		//Добавляем в таблицу
		$query = mysqli_query($con, "INSERT INTO weekends (`date`) VALUES ('$date')");		
	} 
	else if (isset($_POST["del_day"])){
		$date = $_POST["del_day"];
		//Удаление
		$query = mysqli_query($con, "DELETE FROM weekends WHERE date='$date'");			
	}
?>


<?php
	//------------ПОСЕЩЕНИЕ-------------
	if(isset($_POST["chose_date"])){
?>
	<table>
		<tr>
			<th>№</th>
			<th>ФИО</th>
			<th>П</th>
			<th>У</th>
			<th>Б</th>
		</tr>
			<?php
				$curator_ID = $_SESSION["curator_ID"];
				$query = mysqli_query($con, "SELECT * FROM curator_group WHERE curator_ID='$curator_ID'");
				$curator = mysqli_fetch_array($query);
				$c_group = $curator["group_ID"];
				//Находим группу куратора
				$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$c_group'");

				$curr_date = $_POST["chose_date"];

				$date = mysqli_query($con, "SELECT * FROM attend WHERE date='$curr_date' AND curator_ID='$curator_ID'");
				$attend = array();
				while($row = mysqli_fetch_array($date)){
					$attend[$row["student_ID"]]["P"] = $row["P"];
					$attend[$row["student_ID"]]["U"] = $row["U"];
					$attend[$row["student_ID"]]["B"] = $row["B"];
				}

				//№ студента
				$k = 0;
				//Вывод
				while($row = mysqli_fetch_array($query)){
				$k++;
				?>
				<tr>
					<!--Номер пп-->
					<td><?php echo $k; ?></td>
					<!--ФИО-->
					<td><?php echo $row["s_surname"]." ".$row["s_name"]." ".$row["s_father"]; ?></td>
					<td><input type="text" data-student="<?php echo $row["student_ID"]; ?>" name="P" data-date="<?php echo $curr_date; ?>" class="mark P" value="<?php echo $attend[$row["student_ID"]]["P"]; ?>"></td>
					<td><input type="text" data-student="<?php echo $row["student_ID"]; ?>" name="U" data-date="<?php echo $curr_date; ?>" class="mark U" value="<?php echo $attend[$row["student_ID"]]["U"]; ?>"></td>
					<td><input type="text" data-student="<?php echo $row["student_ID"]; ?>" name="B" data-date="<?php echo $curr_date; ?>" class="mark B" value="<?php echo $attend[$row["student_ID"]]["B"]; ?>"></td>
				</tr>
				<?php } ?>	
	</table>
<?php } ?>


<?php
	//Добавление прогулов
	if(isset($_POST["new_mark"])){
		$new_mark = $_POST["new_mark"];
		$mark_st = $_POST["mark_st"];
		$date = $_POST["mark_date"];
		$mark_type = $_POST["mark_type"];
		$curator_ID = $_SESSION["curator_ID"];

		$upd = mysqli_query($con, "SELECT * FROM attend WHERE date='$date' AND student_ID='$mark_st'");
		//Значит добавляют не первый раз
		if(mysqli_num_rows($upd) != 0){
			$query = mysqli_query($con, "UPDATE attend SET $mark_type='$new_mark' WHERE student_ID='$mark_st' AND date='$date'");
		}
		else {
			switch($mark_type){
		    case "P":
		        $query = mysqli_query($con, "INSERT INTO attend (`date`, `student_ID`, `curator_ID`, `P`, `U`, `B`) VALUES ('$date', '$mark_st', '$curator_ID', '$new_mark', '', '')");
		        break;
		    case "U":
		        $query = mysqli_query($con, "INSERT INTO attend (`date`, `student_ID`, `curator_ID`, `P`, `U`, `B`) VALUES ('$date', '$mark_st', '$curator_ID', '', '$new_mark', '')");
		        break;
		    case "B":
		        $query = mysqli_query($con, "INSERT INTO attend (`date`, `student_ID`, `curator_ID`, `P`, `U`, `B`) VALUES ('$date', '$mark_st', '$curator_ID', '', '', '$new_mark')");
		        break;		                     		
			}
		}
	}
?>