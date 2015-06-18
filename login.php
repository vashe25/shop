<?php
session_start();

//стартуем twig!
require_once 'vendor/autoload.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);
//

/*/проверяем залогинился ли пользователь
if ($_SESSION['login'] == NULL) {
	header('Location: login.php');
}
/*/

/*/создание таблицы "users"
$query = "CREATE TABLE `my`.`users`(  
  `id` INT NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(16) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_unicode_ci;";
/*/

//подключаемся к базе данных
$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

$db = new PDO('mysql:host=localhost;dbname=my','root', '', $opt);
//

//вводим действие
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : NULL;

switch ($action) {
		
		//логинимся
	case 'login':
		
		//хэшируем пароль
		$pass = $_POST['pass'];
		$md5_pass = md5($pass);
		$login = $_POST['login'];
		//

		//получаем содержимое таблицы 'users' в массив
		$query = "SELECT * FROM users ORDER BY id ASC";
		$stmnt = $db->prepare($query);
		$stmnt->execute();
		$stmnt = $stmnt->fetchAll();
		//

		//проверяем существует ли 'login' в таблице 'users'
		foreach ($stmnt as $value) {
			//проверяем логин введенный пользователем
			if ($login == $value['login']) {
				//проверяем пароль введенный пользователем
				if ($md5_pass == $value['password']) {
					//записываем в сессию логин и пароль пользователя
					$_SESSION['login'] = $login;
					$_SESSION['password'] = $md5_pass;
					//

					//введенные данные верны
					//echo "Logged In!";
					header('Location: admin.php');
					break;
				}
			}
			else {
				//пользователь ввел не верные данные
				$error = "Error: login or password isn't correct";
				
				echo $twig->render('login.twig', array('login' => $login, 'password' => $pass, 'error' => $error));
				break;
			}
		}
		//
		break;

		//выходим из сессии
	case 'exit':
		
		//стираем логин и пароль пользователя из сессии
		unset($_SESSION['login']);
		unset($_SESSION['password']);
		//
		header('Location: login.php');

		break;

		//регистрируемся
	case 'register':

		//хэшируем пароль
		$pass = $_POST['pass'];
		$md5_pass = md5($pass);
		$login = $_POST['login'];
		//

		//получаем содержимое таблицы 'users' в массив
		$query = "SELECT * FROM users ORDER BY id ASC";
		$stmnt = $db->prepare($query);
		$stmnt->execute();
		$stmnt = $stmnt->fetchAll();
		//
		
		//проверяем существует ли 'login' в таблице 'users'
		foreach ($stmnt as $value) {
			if ($login == $value['login']) {
				
				//этот логин уже занят
				$error = "Error: user already registred. Choose another name.";
				echo $twig->render('login.twig', array('login' => $login, 'password' => $pass, 'error' => $error));

				break;
			}
			else {

				//добавление пользователя в базу данных
				$query = "INSERT INTO users SET login = :login, password = :password";
				$stmnt = $db->prepare($query);
				$stmnt->execute(array(':login' => $login, ':password' => $md5_pass));
				//
				
				echo "Success: user registered";
				break;
			}
		}
		//

		break;	
	default:
		
		echo $twig->render('login.twig', array());
		
		break;
}

?>