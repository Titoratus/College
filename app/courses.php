<?php
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
		//Удаляем
		$query = mysqli_query($con, "DELETE FROM groups WHERE group_ID = '$group'");
	}
?>

<div class="groups">
	<div class="course_1">
		<?php
			//Вывод всех групп 1-го курса
			$query = mysqli_query($con, "SELECT * FROM groups WHERE course = '1'");
			while($row = mysqli_fetch_array($query)){
				//Выводим название группы и ссылку, при на жатии на которую в адресную строку добавляется ID группы
				echo "<div>".$row['group_ID']."<a href='?groupid=".$row['group_ID']."'>Удалить</a></div>";
			}
		?>

		<form action="" method="POST">
			<input type="text" name="course_1">
			<input type="submit" name="addCourse_1" value="Добавить">
		</form>

	</div>
	<div class="course_2"></div>
	<div class="course_3"></div>
	<div class="course_4"></div>
</div>