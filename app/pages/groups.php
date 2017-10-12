<?php
	//Защита от открытия файла через адресную строку
	define("access", true);
	
	$page = "Группы";
	include("../header.php");

	//Если не установлена такая сессия, то возвращаем на вход
	if(!isset($_SESSION["nickname"])){
		header("Location: ../index.php");
	}
?>

<main class="main">
	<!--Содержит все группы-->
	<?php include("../courses.php"); ?>
</main>

<?php include("../footer.php"); ?>