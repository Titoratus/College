<?php
	$page = "Отчёты";
	include("../header.php");
?>
<main class="main">
		<?php
			//Если нет студентов, то ничего не выводим
			$group = $_SESSION["curator_group"];
			$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$group'");
			if(mysqli_num_rows($query) == 0) die("Нет студентов.");
			
			$rus_month = array("Январь","Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
			for($i=0; $i <= 11; $i++){
				$path = "../reports/".$rus_month[$i]." ".$_SESSION["curator_group"].".xls";
				if(file_exists($path)){
					if(date('m', mktime(0,0,0,$i+1,1,date('Y'))) == date('m')){
			?>
			<div class="report">
				<div class="report_month"><?php echo $rus_month[$i]; ?></div>
				<a href="../make_report" class="report_create">Создать</a>
				<a href="../reports/<?php echo $rus_month[$i]." ".$_SESSION["curator_group"].".xls"; ?>" class="report_download">Загрузить</a>
			</div>
			<?php
					}
					else {
			?>
			<div class="report">
				<div class="report_month"><?php echo $rus_month[$i]; ?></div>
				<a href="../reports/<?php echo $rus_month[$i]." ".$_SESSION["curator_group"].".xls"; ?>" class="report_download">Загрузить</a>
			</div>
			<?php
					}
				}
				else if(date('m', mktime(0,0,0,$i+1,1,date('Y'))) == date('m')){
			?>
			<div class="report">
				<div class="report_month"><?php echo $rus_month[$i]; ?></div>
				<a href="../make_report" class="report_create report_create__right">Создать</a>
			</div>			
			<?php
				}
			}
		?>
</main>
<?php include("../footer.php"); ?>