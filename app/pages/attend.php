<?php
	define("access", true);
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
		<h1><?php
			switch (date('M', mktime(0,0,0,9,2,2017))) {
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
<table>
    <tr>
        <th><?php echo implode('</th><th>', $rusweeks); ?></th>
    </tr>
    <?php foreach($weeks as $week => $days){ ?>
    <tr>
        <?php foreach($weekdays as $day){ ?>
        <td>
            <?php
            	if(isset($days[$day])){
            		//Перебор всех дней месяца
            		$date = $days[$day].date('m').date('y');
            		$c_ID = $_SESSION["curator_ID"];
            		//Если есть отметки в данную дату и у данного куратора
            		$query = mysqli_query($con, "SELECT * FROM attend WHERE date='$date' AND curator_ID='$c_ID'");
            		echo "<span ".($days[$day] == date('d') ? "class='active date_on' data-date='$date'" : (mysqli_num_rows($query) == 0 ? "class='date_off'" : "class='date_on' data-date='$date'")).">".$days[$day]."</span>";
            	}
            ?>
        </td>               
        <?php } ?>
    </tr>
    <?php } ?>
</table>
<?php } ?>

<div class="attend_table">
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
					<td><?php echo $row["s_surname"]." ".$row["s_name"]." ".$row["s_father"]; ?></td>
					<td><input type="text" data-student="<?php echo $row["student_ID"]; ?>" class="mark" name="P" data-date="<?php echo $curr_date; ?>" <?php echo (isset($attend[$row["student_ID"]]["B"]) ? "value='".$attend[$row["student_ID"]]["P"]."'" : " ") ?>></td>
					<td><input type="text" data-student="<?php echo $row["student_ID"]; ?>" class="mark" name="U" data-date="<?php echo $curr_date; ?>" <?php echo (isset($attend[$row["student_ID"]]["B"]) ? "value='".$attend[$row["student_ID"]]["U"]."'" : " ") ?>></td>
					<td><input type="text" data-student="<?php echo $row["student_ID"]; ?>" class="mark" name="B" data-date="<?php echo $curr_date; ?>" <?php echo (isset($attend[$row["student_ID"]]["B"]) ? "value='".$attend[$row["student_ID"]]["B"]."'" : " ") ?>></td>
				</tr>
				<?php } ?>	
	</table>
</div>
</main>

<?php include("../footer.php"); ?>