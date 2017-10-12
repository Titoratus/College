<?php
	//Если открыли через адресную строку, то нет доступа
	defined("access") or die("У вас нет доступа!");
	
	//Если нажали "Добавить"
	for($i=1; $i <= 4; $i++){
		if(isset($_POST["addCourse_$i"])){
			//Название группы i-го курса
			$group_ID = $_POST["course_$i"];
			//Заносим в таблицу
			$query = mysqli_query($con, "INSERT INTO groups (`group_ID`, `course`) VALUES ('$group_ID', '$i')");

			//Чтобы не было формы повторной отправки
			header("Location: groups.php");
		}
	}

	//Если нажали "Удалить"
	if(isset($_GET['groupid'])){
		//Получаем ID нужной группы с адресной строки
		$group = $_GET['groupid'];
		//Удаляем, если группа пустая
		$query = mysqli_query($con, "DELETE FROM groups WHERE group_ID = '$group'");

		//Если группа не пустая, то удаляем всех студентов и учителей этой группы
		if(!$query){
			$query = mysqli_query($con, "DELETE FROM students WHERE s_group='$group'");
			$query = mysqli_query($con, "DELETE FROM teacher_group WHERE group_ID='$group'");
			$query = mysqli_query($con, "DELETE FROM groups WHERE group_ID = '$group'");
		}
	}
?>

<div class="groups">
	<?php for($i = 1; $i <= 4; $i++){ ?>
		<div class="<?php echo "course_$i"; ?>">
			<div><?php echo "$i курс"; ?></div>
				<?php
					//Вывод всех групп 1-го курса
					$query = mysqli_query($con, "SELECT * FROM groups WHERE course = '$i'");
					while($row = mysqli_fetch_array($query)){
						//Выводим название группы и ссылку, при на жатии на которую в адресную строку добавляется ID группы
						//Также если сейчас есть выбранная группа, то ставим ей клаа active (через js не работает, т.к. обновляется страница)
						echo "<a class='".((isset($_GET["selected_group"]) && $_GET["selected_group"] == $row['group_ID']) ? "active" : "")."' href='?selected_group=".$row['group_ID']."' class='course_name'>".$row['group_ID']."<a href='?groupid=".$row['group_ID']."'>Удалить</a></a>";
					}
				?>
			<form action="" method="POST">
				<input type="text" name="<?php echo "course_$i"; ?>" required>
				<input type="submit" name="<?php echo "addCourse_$i"; ?>" value="Добавить">
			</form>			
		</div>
	<?php } ?>
</div>

<?php
	//Если нажать удалить студента
	if(isset($_GET["del_stud"])){
		$s_ID = $_GET["del_stud"];
		$query = mysqli_query($con, "SELECT s_group FROM students WHERE student_ID='$s_ID'");
		//Сейчас в строке нет selected_group, поэтому таблица исчезнет после удаления
		//Чтобы избежать, получаем группу удалённого студента и делаем header вместе с GET
		$group = mysqli_fetch_array($query);
		//Удаление
		$query = mysqli_query($con, "DELETE FROM students WHERE student_ID='$s_ID'");
		header("Location: ?selected_group=".$group['s_group']);
	}

	//Таблица студентов появляется только после выбора группы
	if(isset($_GET["selected_group"])){
		//Если добавили нового студента
		if(isset($_POST["new_submit"])){
				$group = $_GET["selected_group"];
				$s_name = $_POST["new_s_name"];
				$s_surname = $_POST["new_s_surname"];
				$s_father = $_POST["new_s_father"];
				$query = mysqli_query($con, "INSERT INTO students (`student_ID`, `s_name`, `s_surname`, `s_father`, `s_group`) VALUES (NULL, '$s_name', '$s_surname', '$s_father', '$group')");
		}
?>

<table>
	<tr>
		<th>#</th>
		<th>ФИО</th>
	</tr>
	<?php
		$group = $_GET["selected_group"];
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