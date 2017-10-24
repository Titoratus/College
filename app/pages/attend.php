<?php
	$page = "Посещение";
	include("../header.php");
	//Если не установлена такая сессия, то возвращаем на вход
	if(!isset($_SESSION["nickname"])){
		header("Location: ../index.php");
	}	
?>
<main class="main">
	<?php
		//Если сегодня выходной, то ничего не выводить
		$curr_date = date('d').date('m').date('y');
		$query = mysqli_query($con, "SELECT * FROM weekends WHERE date='$curr_date'");
		if(mysqli_num_rows($query) != 0) die("Сегодня выходной");

		function getDates($year){
		    $dates = array();

		    for($i = 1; $i <= date('t'); $i++){
		        $month = date('m', mktime(0,0,0,date('m'),$i,$year));
		        $wk = date('W', mktime(0,0,0,date('m'),$i,$year));
		        $wkDay = date('D', mktime(0,0,0,date('m'),$i,$year));
		        $day = date('d', mktime(0,0,0,date('m'),$i,$year));

		        $dates[$month][$wk][$wkDay] = $day;
		    } 
		    return $dates;   
		}
		$dates = getDates(2017); 
		$weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
		$rusweeks = array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'); ?>
		<?php foreach($dates as $month => $weeks) { ?>
		<h1 class="main_title"><?php
			switch (date('M', mktime(0,0,0,date('m'),date('d'),date('Y')))) {
			    case "Jan":
			        echo "Январь";
			        break;
			    case "Feb":
			        echo "Февраль";
			        break;
			    case "Mar":
			        echo "Март";
			        break;
			    case "Apr":
			        echo "Апрель";
			        break;
			    case "May":
			        echo "Май";
			        break;
			    case "Jun":
			        echo "Июнь";
			        break;
			    case "Jul":
			        die("Каникулы");
			        break;	   
			    case "Aug":
			        die("Каникулы");
			        break;	  	             
			    case "Sep":
			        echo "Сентябрь";
			        break;	  
			    case "Oct":
			        echo "Октябрь";
			        break;	  
			    case "Nov":
			        echo "Ноябрь";
			        break;	  
			    case "Dec":
			        echo "Декабрь";
			        break;	 	        	        	                    
			}
		?></h1>
<!--КАЛЕНДАРЬ НА МЕСЯЦ-->
<div class="month">
<table class="month_table">
    <tr>
        <th class="week_name"><?php echo implode('</th><th class="week_name">', $rusweeks); ?></th>
    </tr>
    <?php foreach($weeks as $week => $days){ ?>
    <tr>
        <?php foreach($weekdays as $day){ ?>
        <td>
            <?php
            	if(isset($days[$day])){
            		//Перебор всех дней месяца
            		$date = $days[$day].date('m').date('y');
            		$curr_date = date('d').date('m').date('y');
								$query = mysqli_query($con, "SELECT * FROM weekends WHERE date='$date'");
								      		
            		echo "<span class='weekend ".($days[$day] == date('d') ? "weekend__red date_on' data-date='$date'" : (($date > $curr_date || mysqli_num_rows($query) != 0) ? "date_off'" : "date_on' data-date='$date'")).">".$days[$day]."</span>";
            	}
            ?>
        </td>               
        <?php } ?>
    </tr>
    <?php } ?>
</table>
</div>
<?php } ?>

<div class="group_table">
<div class="attend_table">
	<table class="table">
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

				$curr_date = date('d').date('m').date('y');

				$date = mysqli_query($con, "SELECT * FROM attend WHERE date='$curr_date' AND curator_ID='$curator_ID'");
				if(mysqli_num_rows($date) != 0){
					$attend = array();
					while($row = mysqli_fetch_array($date)){
						$attend[$row["student_ID"]]["P"] = $row["P"];
						$attend[$row["student_ID"]]["U"] = $row["U"];
						$attend[$row["student_ID"]]["B"] = $row["B"];
					}
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
					<td><input type="text" maxlength="2" data-student="<?php echo $row["student_ID"]; ?>" data-date="<?php echo $curr_date; ?>" class="mark" name="P" <?php echo ((isset($P) && $P != 0) ? "value='".$P."'" : " ") ?>"></td>
					<td><input type="text" maxlength="2" data-student="<?php echo $row["student_ID"]; ?>" data-date="<?php echo $curr_date; ?>" class="mark" name="U" <?php echo ((isset($U) && $U != 0) ? "value='".$U."'" : " ") ?>"></td>
					<td><input type="text" maxlength="2" data-student="<?php echo $row["student_ID"]; ?>" data-date="<?php echo $curr_date; ?>" class="mark" name="B" <?php echo ((isset($B) && $B != 0) ? "value='".$B."'" : " ") ?>"></td>
				</tr>
				<?php } ?>	
	</table>
</div>
</div>
</main>

<?php include("../footer.php"); ?>