<?php
	//Если открыли через адресную строку, то нет доступа
	defined("access") or die("У вас нет доступа!");
?>

<div class="groups">
	<?php
		$curator_ID = $_SESSION["curator_ID"];
		$query = mysqli_query($con, "SELECT * FROM curator_group WHERE curator_ID = '$curator_ID'");
		//Если нет группы
		if(mysqli_num_rows($query)==0){
			?>
			<input class="new_group" placeholder="№ группы" maxlength="3" type="text">
			<div style="display: none;" class="error"></div>
			<?php
		}
		else {
			$group = mysqli_fetch_array($query);
			$group = $group["group_ID"];
			?>
			<div data-group="<?php echo $group; ?>" class="group_name"><?php echo $group; ?><span class="edit_group">edit</span></div>
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
	<?php } ?>
</div>