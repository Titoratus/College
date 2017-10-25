<?php
	session_start();

	//Подключение к БД и установка кодировки для кириллицы
	$con = mysqli_connect("localhost", "root", "", "college");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");

	// Подключаем класс для работы с excel
	require_once('phpExcel/Classes/PHPExcel.php');
	require_once('phpExcel/Classes/PHPExcel/Writer/Excel5.php');
	// Создаем объект класса PHPExcel
	$xls = new PHPExcel();
	// Устанавливаем индекс активного листа
	$xls->setActiveSheetIndex(0);
	// Получаем активный лист
	$sheet = $xls->getActiveSheet();
	// Подписываем лист
	$sheet->setTitle('Отчёт');

	$sheet->getColumnDimension('A')->setWidth(2.7);
	$sheet->getColumnDimension('B')->setWidth(26.7);

	//Вывод даты
	$curr_date = date('d').".".date('m').".".date('Y');
	$sheet->setCellValue("B1", $curr_date);

	//Вывод ФИО куратора
	$sheet->mergeCells('B2:M2');	
	$curator_ID = $_SESSION["curator_ID"];
	$query = mysqli_query($con, "SELECT * FROM curators WHERE curator_ID='$curator_ID'");
	$query = mysqli_fetch_array($query);
	$curator = "Куратор группы: ".$query["c_surname"]." ".$query["c_name"]." ".$query["c_father"];
	$sheet->setCellValue("B2", $curator);

	//Вывод студентов
	$query = mysqli_query($con, "SELECT * FROM curator_group WHERE curator_ID='$curator_ID'");
	$query = mysqli_fetch_array($query);
	$group = $query["group_ID"];
	$query = mysqli_query($con, "SELECT * FROM students WHERE s_group='$group' GROUP BY s_name ASC");

	//Счётчик студентов
	$s = 0;
	//Вывод студентов и прогулов
	while($row = mysqli_fetch_array($query)){
		$s++;
		$num = "A".(4+$s);
		$sheet->setCellValue($num, $s);
		$name = "B".(4+$s);
		//Вставка имени и фамилии без отчества
		$sheet->setCellValue($name, substr($row["s_name"], 0, strrpos($row["s_name"], " ")));

		//Вывод прогулов
		$student = $row["student_ID"];
		$attend = mysqli_query($con, "SELECT * FROM attend WHERE student_ID='$student'");
		$col = "C";
		$all_P = 0; $all_U = 0; $all_B = 0;
		while($row2 = mysqli_fetch_array($attend)){
			$day = substr($row2["date"], 0, 2);
			if($row2["P"] != 0) {
				for($i=1; $i < $day; $i++){ $col++; }
				$sheet->setCellValue($col.($s+4), $row2["P"]);
				$sheet->getStyle($col.($s+4))->applyFromArray(
				    array(
				        'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => 'F34B4B')
				        )
				    )
				);				
				$col = "C";
				$all_P += $row2["P"];	
			}
			if($row2["U"] != 0) {
				for($i=1; $i < $day; $i++){ $col++; }
				$sheet->setCellValue($col.($s+4), $row2["U"]);
				$sheet->getStyle($col.($s+4))->applyFromArray(
				    array(
				        'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => '4FCC70')
				        )
				    )
				);				
				$col = "C";
				$all_U += $row2["U"];
			}			
			if($row2["B"] != 0) {
				for($i=1; $i < $day; $i++){ $col++; }
				$sheet->setCellValue($col.($s+4), $row2["B"]);
				$sheet->getStyle($col.($s+4))->applyFromArray(
				    array(
				        'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => '88B7FF')
				        )
				    )
				);				
				$col = "C";
				$all_B += $row2["B"];
			}				
		}

		//Вставка всего У, П, Б за день
		$last_day = date('t') + 2;
		$col = "A";
		$col2 = "A";
		for($i=1; $i <= $last_day; $i++){ $col++; $col2++; }
		$sheet->setCellValue($col.($s+4), $all_U);
		$col++;
		$sheet->setCellValue($col.($s+4), $all_B);
		$col++;
		$sheet->setCellValue($col.($s+4), $all_P);
		$col++;
		$from = $col2;
		$col2++;
		$col2++;
		$to = $col2;
		$sheet->setCellValue($col.($s+4), "=SUM(".$from.($s+4).":".$to.($s+4).")");
	}

	//Всего за месяц У, П, Б, ВСЕГО
	for($l=1; $l <=4; $l++){		
		$sheet->setCellValue($from.($s+5), "=SUM(".$from."5:".$from.($s+4).")");
		$from++;
	}

	//Вывод кол-ва дней
	$curr_days = date('t');
	$col = "C";
	for($i = 1; $i <= $curr_days; $i++){
		$sheet->getColumnDimension($col)->setWidth(2.7);
		$sheet->setCellValue($col."4", $i);

		//Проверка на выходной день
		$date = date('d', mktime(0,0,0,date('m'), $i, date('Y')));
		$date = $date.date('m').date('y');
		$check = mysqli_query($con, "SELECT * FROM weekends WHERE date='$date'");
		if(mysqli_num_rows($check) != 0){
			$sheet->getStyle($col.'4:'.$col.(4+$s))->applyFromArray(
			    array(
			        'fill' => array(
			            'type' => PHPExcel_Style_Fill::FILL_SOLID,
			            'color' => array('rgb' => 'A6A6A6')
			        )
			    )
			);	
		}
		$col++;
	}	

	//Подписи У, Б, П, ВСЕГО
	$sheet->setCellValue($col."4", "У"); $sheet->getColumnDimension($col)->setWidth(6.2); $col++;
	$sheet->setCellValue($col."4", "Б"); $sheet->getColumnDimension($col)->setWidth(6.2); $col++;
	$sheet->setCellValue($col."4", "П"); $sheet->getColumnDimension($col)->setWidth(6.2); $col++;
	$sheet->setCellValue($col."4", "Всего"); $sheet->getColumnDimension($col)->setWidth(6.2);

	//Подписи значения цветов
	$sheet->mergeCells('D'.($s+5).':I'.($s+5));
	$sheet->setCellValue("D".($s+5), "Прогулы");
	$sheet->getStyle("C".($s+5))->applyFromArray(
	    array(
	        'fill' => array(
	            'type' => PHPExcel_Style_Fill::FILL_SOLID,
	            'color' => array('rgb' => 'F34B4B')
	        )
	    )
	);	
	$sheet->mergeCells('D'.($s+6).':I'.($s+6));
	$sheet->setCellValue("D".($s+6), "Уваж. причины");
	$sheet->getStyle("C".($s+6))->applyFromArray(
	    array(
	        'fill' => array(
	            'type' => PHPExcel_Style_Fill::FILL_SOLID,
	            'color' => array('rgb' => '4FCC70')
	        )
	    )
	);		
	$sheet->mergeCells('D'.($s+7).':I'.($s+7));
	$sheet->setCellValue("D".($s+7), "По болезни");
	$sheet->getStyle("C".($s+7))->applyFromArray(
	    array(
	        'fill' => array(
	            'type' => PHPExcel_Style_Fill::FILL_SOLID,
	            'color' => array('rgb' => '88B7FF')
	        )
	    )
	);

	//Установка границ
	$sheet->getStyle("A4:".$col.($s+4))->applyFromArray(
	    array(
	        'borders' => array(
	            'allborders' => array(
	                'style' => PHPExcel_Style_Border::BORDER_THIN,
	                'color' => array('rgb' => '757171')
	            )
	        )
	    )
	);

	//Сохранение
	$objWriter = new PHPExcel_Writer_Excel5($xls);
	$month = array("Январь","Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
	for($i=0; $i <= 11; $i++){
		if(date('m', mktime(0,0,0,$i+1,1,date('Y'))) == date('m')) {
			$today = $month[$i];
			break;
		}
	}

	$objWriter->save('reports/'.$today.' '.$_SESSION['curator_group'].'.xls');
	mysqli_close($con);
	header("Location: pages/reports.php");
?>