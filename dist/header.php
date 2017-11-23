<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<script src="<?php if($page != "Вход") echo "../"; ?>js/font-loader.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php if($page != "Вход") echo "../"; ?>css/main.min.css">
	<title><?php echo $page; ?></title>
</head>
<body <?php if($page == "Вход") echo "class='login'"; ?>>
<?php
	session_start();

	//Подключение к БД и установка кодировки для кириллицы
	include("db.php");

	if(isset($_SESSION["nickname"])){
?>
<div class="wrapper">
<!--МЕНЮ-->
<header class="header">
	<nav class="header_menu">
		<ul class="menu">
			<li class="menu_elem"><a class="menu_link <?php if($page == 'Моя группа'){echo 'link__active';} ?>" href="group">Моя группа</a></li>
			<li class="menu_elem"><a class="menu_link <?php if($page == 'Выходные'){echo 'link__active';} ?>" href="weekends">Выходные</a></li>
			<li class="menu_elem"><a class="menu_link <?php if($page == 'Посещение'){echo 'link__active';} ?>" href="attend">Посещение</a></li>
			<li class="menu_elem"><a class="menu_link <?php if($page == 'Отчёты'){echo 'link__active';} ?>" href="reports">Отчёты</a></li>
		</ul>
	</nav>
	<div class="header_infobar">
		<?php
			$nickname = $_SESSION["nickname"];
			$query = mysqli_query($con, "SELECT * FROM curators WHERE nickname = '$nickname'");
			$query = mysqli_fetch_array($query);

			echo $query["c_surname"]." ".substr($query["c_name"], 0, 2).". ".substr($query["c_father"], 0, 2).".";
		?>
		<a id="logout" href="../logout">Выйти</a>
	</div>
</header>

<?php } ?>