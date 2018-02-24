<?php
	session_start();
	include("db.php");
	//-------ВХОД------------
	if(isset($_POST["login"])){
		$login = $_POST["login"];
		$password = md5($_POST["password"]);

		//Делаем выборку по нику
		$query = mysqli_query($con, "SELECT * FROM curators WHERE nickname = '$login' AND password = '$password'");
		//Если такой есть
		if(mysqli_num_rows($query) != 0){
			$_SESSION["nickname"] = $_POST["login"];
			$nickname = $_POST["login"];
			$query = mysqli_query($con, "SELECT * FROM curators WHERE nickname='$nickname'");
			$query = mysqli_fetch_array($query);
			$_SESSION["curator_ID"] = $query["curator_ID"];
			$curator_ID = $query["curator_ID"];
			$query = mysqli_query($con, "SELECT * FROM curator_group WHERE curator_ID='$curator_ID'");
			$query = mysqli_fetch_array($query);

			$_SESSION["curator_group"] = $query["group_ID"];
			echo "no_errors";
		}
		else echo "Неверный логин или пароль!";
	}	

	//-----------ГРУППЫ-----------
	//Первое создание группы или изменение
	if(isset($_POST["new_group"]) || isset($_POST["edit_group"])){

		//Если изменили название группы
		if(isset($_POST["edit_group"])){
			$group = $_POST["edit_group"];
			if(!is_numeric($group)) die("not_numeric");
			$group_ID = $group;
			$old_group = $_POST["old_group"];
			$course = $_POST["course"];
			$query = mysqli_query($con, "UPDATE groups SET group_ID='$group', course='$course' WHERE group_ID='$old_group'");
			if(!$query) die("group_exists");
			$_SESSION["curator_group"] = $group;
		}

		//Если впервые создали группу
		if(isset($_POST["new_group"])){
			$group_ID = $_POST["new_group"];
			if(!is_numeric($group_ID)) die("not_numeric");
			$group_course = $_POST["course"];

			//Заносим в groups
			$query = mysqli_query($con, "INSERT INTO groups (`group_ID`, `course`) VALUES ('$group_ID', '$group_course')");
			if(!$query) die("group_exists");
			$_SESSION["curator_group"] = $group_ID;			

			//Заносим в curator_group
			$curator_ID = $_SESSION["curator_ID"];
			$query = mysqli_query($con, "INSERT INTO curator_group (`curator_ID`, `group_ID`) VALUES ('$curator_ID', '$group_ID')");

			$query = mysqli_query($con, "SELECT * FROM curator_group WHERE curator_ID='$curator_ID'");
			$group = mysqli_fetch_array($query);
			$group = $group["group_ID"];
		}
		//Вывод студентов группы
	?>
	<div data-group="<?php echo $group_ID; ?>" class="group_name"><?php echo $group_ID; ?><span class="btn_edit_group"></span><div class="error"></div></div>
	<div class="group_table">
		<table class="table">
			<tr>
				<th class="table_num">#</th>
				<th class="table_name">ФИО студента</th>
			</tr>
			<?php
				$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$group' ORDER BY s_name ASC");
				if(mysqli_num_rows($query) == 0) echo "<tr><td colspan='2'>Студентов пока нет.</td></tr>";
				
				$s = 0;
				while($row = mysqli_fetch_array($query)){
					$s=$s+1;
					echo "<tr><td>".$s."</td><td data-group='".$group."' data-student='".$row['student_ID']."'><span>".$row['s_name']."</span><a class='edit_stud'></a><a class='del_stud'></a></td></tr>";
				}
			?>
		</table>

		<form id="add_student" action="" method="POST">
			<input type="text" data-group="<?php echo $group; ?>" name="new_s_name" placeholder="ФИО нового студента" autocomplete="off" required>
			<input type="submit" value="+">
		</form>		
	</div>
<?php
	}




	//Добавление, удаление студента
	if(isset($_POST["new_s_name"]) || isset($_POST["del_stud"]) || isset($_POST["edit_stud"])){
		//Во всех случаях есть post[group]
		$group = $_POST["group"];		
		//Если добавили нового студента
		if(isset($_POST["new_s_name"])){
			//Первые буквы в верхний регистр
			$s_name = mb_convert_case($_POST["new_s_name"], MB_CASE_TITLE, "UTF-8");
			$query = mysqli_query($con, "INSERT INTO students (`student_ID`, `s_name`, `s_group`) VALUES (NULL, '$s_name', '$group')");
		}
		//Если нажали удалить студента
		if(isset($_POST["del_stud"])){
			$s_ID = $_POST["del_stud"];
			$query = mysqli_query($con, "DELETE FROM students WHERE student_ID='$s_ID'");	
		}
		//Если нажали редактировать студента
		if(isset($_POST["edit_stud"])){
			$s_ID = $_POST["edit_stud"];
			$new_name = $s_name = mb_convert_case($_POST["new_name"], MB_CASE_TITLE, "UTF-8");
			$query = mysqli_query($con, "UPDATE students SET s_name='$new_name' WHERE student_ID='$s_ID'");
		}
?>
	<table class="table">
		<tr>
			<th class="table_num">#</th>
			<th class="table_name">ФИО студента</th>
		</tr>
		<?php
			$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$group' ORDER BY s_name ASC");
			if(mysqli_num_rows($query) == 0) echo "<tr><td colspan='2'>Студентов пока нет.</td></tr>";

			$s = 0;
			while($row = mysqli_fetch_array($query)){
				$s=$s+1;
				echo "<tr><td>".$s."</td><td data-group='".$group."' data-student='".$row['student_ID']."'><span>".$row['s_name']."</span><a class='edit_stud'></a><a class='del_stud'></a></td></tr>";
			}
		?>
	</table>

	<form id="add_student" action="" method="POST">
		<input type="text" data-group="<?php echo $group; ?>" name="new_s_name" autocomplete="off" placeholder="ФИО нового студента" required>
		<input type="submit" value="+">
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
		$query = mysqli_query($con, "DELETE FROM attend WHERE date='$date'");
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
	<table class="table" data-aos="zoom-in" data-aos-duration='700'>
		<tr>
			<th>#</th>
			<th>ФИО студента</th>
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
				$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$c_group' ORDER BY s_name ASC");

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
					<td><?php echo $row["s_name"]; ?></td>
					
					<?php
						(isset($attend[$row["student_ID"]]["P"])) ? $P = $attend[$row["student_ID"]]["P"] : $P=0;
						(isset($attend[$row["student_ID"]]["U"])) ? $U = $attend[$row["student_ID"]]["U"] : $U=0;
						(isset($attend[$row["student_ID"]]["B"])) ? $B = $attend[$row["student_ID"]]["B"] : $B=0;
					?>										
					<td><input type="text" maxlength="3" data-student="<?php echo $row["student_ID"]; ?>" data-date="<?php echo $curr_date; ?>" class="mark" name="P" <?php echo ((isset($P) && $P != 0) ? "value='".$P."'" : " ") ?>"></td>
					<td><input type="text" maxlength="3" data-student="<?php echo $row["student_ID"]; ?>" data-date="<?php echo $curr_date; ?>" class="mark" name="U" <?php echo ((isset($U) && $U != 0) ? "value='".$U."'" : " ") ?>"></td>
					<td><input type="text" maxlength="3" data-student="<?php echo $row["student_ID"]; ?>" data-date="<?php echo $curr_date; ?>" class="mark" name="B" <?php echo ((isset($B) && $B != 0) ? "value='".$B."'" : " ") ?>"></td>
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
		$P = 0; $U = 0; $B = 0;
		$upd = mysqli_query($con, "SELECT * FROM attend WHERE date='$date' AND student_ID='$mark_st'");

		//Добавление в первый раз
		switch($mark_type){
			case "P":
				$P = $new_mark;
				break;
			case "U":
				$U = $new_mark;
				break;
			case "B":
				$B = $new_mark;
				break;
		}
		if(mysqli_num_rows($upd) == 0){
			$query = mysqli_query($con, "INSERT INTO attend (`date`, `student_ID`, `curator_ID`, `P`, `U`, `B`) VALUES ('$date', '$mark_st', '$curator_ID', '$P', '$U', '$B')");
		}
		else {
			//Значение только в одном поле, поэтому удаляем старое
			$query = mysqli_query($con, "DELETE FROM attend WHERE date='$date' AND student_ID='$mark_st'");
			$query = mysqli_query($con, "INSERT INTO attend (`date`, `student_ID`, `curator_ID`, `P`, `U`, `B`) VALUES ('$date', '$mark_st', '$curator_ID', '$P', '$U', '$B')");
		}
	}
?>