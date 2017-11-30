<?php
	//Защита от открытия файла через адресную строку
	define("access", true);
	
	$page = "Моя группа";
	include("../header.php");

	//Если не установлена такая сессия, то возвращаем на вход
	if(!isset($_SESSION["nickname"])){
		header("Location: ../index.php");
	}
?>

<main class="main main_nopadding">
	<div class="group">
		<?php
			$curator_ID = $_SESSION["curator_ID"];
			$query = mysqli_query($con, "SELECT * FROM curator_group WHERE curator_ID = '$curator_ID'");
			//Если нет группы
			if(mysqli_num_rows($query)==0){
				?>
				<div class="group_name"><input class="new_group" placeholder="№ группы" maxlength="3" type="text" autofocus><div style="display: none;" class="error"></div></div>
				<?php
			}
			else {
				$group = mysqli_fetch_array($query);
				$group = $group["group_ID"];
				?>
				<div data-group="<?php echo $group; ?>" class="group_name"><?php echo $group; ?><span class="btn_edit_group"></span><div class="error"></div></div>
				<div class="group_table">
					<table class="table">
						<tr>
							<th class="table_num">#</th>
							<th class="table_name">ФИО студента</th>
						</tr>
						<?php
							$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$group' ORDER BY s_name ASC");
							//Если нет студентов
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
		<?php } ?>
	</div>	
</main>

<?php include("../footer.php"); ?>