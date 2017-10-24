<?php
	//Нужно для защиты include файлов при прямом обращении
	define("access", true);
	$page = "Вход";

	//В header есть session_start(), если СНАЧАЛА поставить проверку на ник, то она не пройдет
	include("header.php");

	//Если есть такая сессия, значит выполнен вход и отправляем на главную
	if(isset($_SESSION["nickname"])){
		header("Location: pages/group.php");
	}
?>

<form class="form_login" action="" method="POST">
	<input class="field field_login" type="text" name="login" placeholder="Логин" autocomplete="off" required>
	<input class="field field_login" type="password" name="password" placeholder="Пароль" required>
	<div class="login_error"></div>
	<input class="login_btn" type="submit" value="Войти">
</form>

<?php include("footer.php"); ?>