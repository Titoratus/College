<?php
	//Если открыли через адресную строку, то нет доступа
	defined("access") or die("У вас нет доступа!");
?>

<div class="groups">
	<?php for($i = 1; $i <= 4; $i++){ ?>
		<div class="<?php echo "course_$i"; ?>">
			<div><?php echo "$i курс"; ?></div>
			<div class="course_content">
				<?php
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
			</div>
		</div>
	<?php } ?>
	<div class="groups_table"></div>
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