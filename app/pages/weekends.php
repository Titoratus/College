<?php
	define("access", true);
	$page = "Выходные";
	include("../header.php");

	//Если не установлена такая сессия, то возвращаем на вход
	if(!isset($_SESSION["nickname"])){
		header("Location: ../index.php");
	}	
?>

<main class="main">
	<h1>Выходные</h1>
	<?php
		//-----------ВЫВОД КАЛЕНДАРЯ-----------
		function getDates($year)
		{
		    $dates = array();
		    //Проверка високосного года
				date("L", mktime(0,0,0, 7,7, $year)) ? $leap = 366 : $leap = 365;

		    for($i = 1; $i <= $leap; $i++){
		        $month = date('m', mktime(0,0,0,1,$i,$year));
		        $wk = date('W', mktime(0,0,0,1,$i,$year));
		        $wkDay = date('D', mktime(0,0,0,1,$i,$year));
		        $day = date('d', mktime(0,0,0,1,$i,$year));

		        $dates[$month][$wk][$wkDay] = $day;
		    } 
		    return $dates;   
		}
	?>
	<?php
	$year_now = date('Y');
	$dates = getDates($year_now); 
	$weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
	$rusweeks = array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс');
	$rusmonths = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'); ?>

	<?php
		//Извлекаем все выходные и проверяем на соответствие
		$query = mysqli_query($con, "SELECT * FROM weekends");
		$freedays = array();
		while($row = mysqli_fetch_array($query)){
			$freedays[$row['date']] = 1;
		}

		//Номер месяца
		$k = 0;
		foreach($dates as $month => $weeks) { 
			echo "<h2>".$rusmonths[$k]."</h2>";
			$k=$k+1;
		?>
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
	            		$cur_date = date('d', mktime(0,0,0,1,$days[$day],$year_now)).date('m', mktime(0,0,0,$k,$days[$day],$year_now)).substr($year_now, 2);
	            		echo "<span class='weekend ".(isset($freedays[$cur_date]) ? "active" : "")."' for='".$cur_date."'>".$days[$day]."</span>";
	            	}
	            	else echo '&nbsp';
	            ?>
	        </td>               
	        <?php } ?>
	    </tr>
	    <?php } ?>
	</table>
	<?php } ?>
</main>
<?php include("../footer.php"); ?>