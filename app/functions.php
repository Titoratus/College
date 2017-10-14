<?php
	session_start();
	$con = mysqli_connect("localhost", "root", "", "college");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");

	//-----------ГРУППЫ-----------
	//Если нажали "Добавить"
	for($i=1; $i <= 4; $i++){
		if(isset($_POST["course_$i"])){
			//Название группы i-го курса
			$group_ID = $_POST["course_$i"];
			//Заносим в таблицу
			$query = mysqli_query($con, "INSERT INTO groups (`group_ID`, `course`) VALUES ('$group_ID', '$i')");
			//Добавляем группу к учителю
			$nickname = $_SESSION["nickname"];
			$query = mysqli_query($con, "SELECT * FROM teachers WHERE nickname='$nickname'");
			$query = mysqli_fetch_array($query);
			$t_ID = $query["teacher_ID"];
			$query = mysqli_query($con, "INSERT INTO teacher_group (`teacher_ID`, `group_ID`) VALUES ('$t_ID', '$group_ID')");

			//Вывод всех групп i-го курса
			$query = mysqli_query($con, "SELECT * FROM groups WHERE course = '$i'");
			while($row = mysqli_fetch_array($query)){						
				echo "<span data-group='".$row["group_ID"]."' class='course_name'>".$row['group_ID']."<a class='del_group' data-group='".$row['group_ID']."'>X</a></span>";
			}
		?>
			<form class="add_group" action="" method="POST">
				<input type="text" name="<?php echo "course_$i"; ?>" required>
				<input type="submit" value="Добавить">
			</form>		
		<?php
		}
	}

	//Если нажали "Удалить"
	if(isset($_POST['del_group'])){
		//Получаем ID нужной группы с адресной строки
		$group = $_POST['del_group'];
		//Удаляем, если группа пустая
		$query = mysqli_query($con, "DELETE FROM groups WHERE group_ID = '$group'");

		//Если группа не пустая, то удаляем всех студентов и учителей этой группы
		if(!$query){
			$query = mysqli_query($con, "DELETE FROM students WHERE s_group='$group'");
			$query = mysqli_query($con, "DELETE FROM teacher_group WHERE group_ID='$group'");
			$query = mysqli_query($con, "DELETE FROM groups WHERE group_ID = '$group'");
		}
	}	

		//Если добавили нового студента
		/*if(isset($_POST["new_submit"])){
				$group = $_GET["selected_group"];
				$s_name = $_POST["new_s_name"];
				$s_surname = $_POST["new_s_surname"];
				$s_father = $_POST["new_s_father"];
				$query = mysqli_query($con, "INSERT INTO students (`student_ID`, `s_name`, `s_surname`, `s_father`, `s_group`) VALUES (NULL, '$s_name', '$s_surname', '$s_father', '$group')");
		}*/

	//Выбранная группа (вывод студентов)
	if(isset($_POST["selected_group"])){
?>
<table>
	<tr>
		<th>#</th>
		<th>ФИО</th>
	</tr>
	<?php
		$group = $_POST["selected_group"];
		$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$group'");

		$s = 0;
		while($row = mysqli_fetch_array($query)){
			$s=$s+1;
			echo "<tr><td>".$s."</td><td>".$row['s_surname']." ".$row['s_name']." ".$row['s_father']."<a href='?del_stud=".$row['student_ID']."'>Удалить</a></td></tr>";
		}
	?>
</table>

<form action="" method="POST">
	<input type="text" name="new_s_surname" required>
	<input type="text" name="new_s_name" required>
	<input type="text" name="new_s_father" required>
	<input type="submit" name="new_submit" value="Добавить">
</form>

<?php } ?>



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