<?php
	define("access", true);
	$page = "Вход";

	//В header есть session_start(), если СНАЧАЛА поставить проверку на ник, то она не пройдет
	include("header.php");

	//Если есть такая сессия, значит выполнен вход и отправляем на главную
	if(isset($_SESSION["nickname"])){
		header("Location: pages/groups.php");
	}
?>

<form action="" method="POST">
	<input type="text" name="login" required>
	<input type="password" name="password" required>
	<input type="submit" name="submit" value="Войти">
</form>

<?php
	//Если нажали submit
	if(isset($_POST["submit"])){
		$login = $_POST["login"];
		$password = $_POST["password"];

		//Делаем выборку по нику
		$query = mysqli_query($con, "SELECT * FROM teachers WHERE nickname = '$login'");
		$query = mysqli_fetch_array($query);

		//Если такой есть
		if(!empty($query["nickname"])){
			//И совпадает пароль, то идём на главную
			if($query["password"] == $_POST["password"]){					
				$_SESSION["nickname"] = $_POST["login"];
				header("Location: pages/groups.php");	
			}
			else echo "Неверный пароль!";
		}
		else echo "Пользователь с таким логином не найден!";
	}

	include("footer.php");
?>