<?php defined("access") or die("У вас не прав!"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="<?php if($page != "Вход"){echo "../";} ?>css/main.css">
	<title><?php echo $page; ?></title>
	<script src="<?php if($page != "Вход"){echo "../";} ?>libs/jquery/dist/jquery.min.js"></script>
</head>
<body>
<?php
	session_start();

	//Подключение к БД и установка кодировки для кириллицы
	$con = mysqli_connect("localhost", "root", "", "college");
	$query = mysqli_query($con, "SET NAMES UTF8");
	$query = mysqli_query($con, "SET CHARACTER SET UTF8");

	if(isset($_SESSION["nickname"])){
?>

<!--МЕНЮ-->
<header class="header">
	<nav class="header_menu">
		<ul>
			<li class="menu_elem"><a class="menu_link <?php if($page == 'Группы'){echo 'active';} ?>" href="groups.php">Группы</a></li>
			<li class="menu_elem"><a class="menu_link <?php if($page == 'Выходные'){echo 'active';} ?>" href="weekends.php">Выходные</a></li>
			<li class="menu_elem"><a class="menu_link <?php if($page == 'Посещение'){echo 'active';} ?>" href="attend.php">Посещение</a></li>
		</ul>
	</nav>
	<div class="header_infobar">
		<?php
			$nickname = $_SESSION["nickname"];
			$query = mysqli_query($con, "SELECT * FROM teachers WHERE nickname = '$nickname'");
			$query = mysqli_fetch_array($query);

			echo $query["t_surname"];
		?>
		<a id="logout" href="../logout.php">Выйти</a>
	</div>
</header>

<?php } ?>