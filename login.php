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
$query = "CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `activation` tinyint(1) NOT NULL,
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
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
		$hash = $login . $md5_pass;
		$hash = md5($hash);
		//

		//получаем содержимое таблицы users в массив
		$query = "SELECT * FROM users ORDER BY id ASC";
		$stmnt = $db->prepare($query);
		$stmnt->execute();
		$stmnt = $stmnt->fetchAll();
		//
		
		//в случае пустой таблицы
		if (empty($stmnt)) {
			//добавление пользователя в базу данных
			$query = "INSERT INTO users SET login = :login, password = :password, activation = '0', hash = :hash";
			$stmnt = $db->prepare($query);
			$stmnt->execute(array(':login' => $login, ':password' => $md5_pass, ':hash' => $hash));
			//
			
			//отправим письмишко на почту пользователю с ссылкой активации
			$message = '<html><head></head><body><p><a href=\"http://shop/login.php?action=activate&hash=' . $hash . '\">Push to activate your account!</a></p></body></html>';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

			$mail = mail('somebody@ya.ru', 'Account activation', $message, $headers);
			//
			
			$error = "Success: user registered";
			echo $twig->render('login.twig', array('login' => $login, 'password' => 'Enter password', 'error' => $error));
		}
		//
		else {
			//проверяем существует ли login в таблице users
			foreach ($stmnt as $value) {
				if ($login == $value['login']) {
					
					//этот логин уже занят
					$error = "Error: user already registred. Choose another name.";
					echo $twig->render('login.twig', array('login' => $login, 'password' => $pass, 'error' => $error));

					break;
				}
				else {

					//добавление пользователя в базу данных
					$query = "INSERT INTO users SET login = :login, password = :password, activation = '0', hash = :hash";
					$stmnt = $db->prepare($query);
					$stmnt->execute(array(':login' => $login, ':password' => $md5_pass, ':hash' => $hash));
					//
					
					//отправим письмишко на почту пользователю с ссылкой активации
					$message = '<html><head></head><body><p><a href=\"http://shop/login.php?action=activate&hash=' . $hash . '\">Push to activate your account!</a></p></body></html>';
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

					$mail = mail('somebody@ya.ru', 'Account activation', $message, $headers);
					//
					
					$error = "Success: user registered";
					echo $twig->render('login.twig', array('login' => $login, 'password' => 'Enter password', 'error' => $error));
					
					break;
				}

			}
			//
		}
		
		break;

		//активируемся
	case 'activate':
		//берем хэш из ссылки
		$hash = $_GET['hash'];

		//ставим единичку в поле activation
		$query = "UPDATE users SET activation = '1' WHERE hash = :hash";
		$stmnt = $db->prepare($query);
		$stmnt->execute(array(':hash' => $hash));	
		//
		
		//достанем логин, который активируется
		$row = $db->query("SELECT * FROM users WHERE id = " . $db->lastInsertId());
		$row = $row->fetchAll();
		//

		$error = "Success: your account is activated";
		echo $twig->render('login.twig', array('login' => $row['login'], 'error' => $error));
		break;

	default:
		
		echo $twig->render('login.twig', array());
		
		break;
}

unset($db);

?>