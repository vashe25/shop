<?php
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

$md5_pass = md5($_GET['pass']);
$login = $_GET['login'];
echo "<pre>";
var_dump($login);
var_dump($md5_pass);

//получаем содержимое таблицы 'users' в массив
$query = "SELECT * FROM users ORDER BY id ASC";
$stmnt = $db->prepare($query);
$stmnt->execute();
$stmnt = $stmnt->fetchAll();
//
echo "<pre>";
var_dump($stmnt);

//проверяем существует ли 'login' в таблице 'users'
foreach ($stmnt as $value) {
	if ($login == $value['login']) {
		echo "user is set";
		break;
	}
	else {
		echo "continue registration";

		/*/добавление пользователя в базу данных
		$query = "INSERT INTO users SET login = :login, password = :password";
		$stmnt = $db->prepare($query);
		$stmnt->execute(array(':login' => $login, ':password' => $md5_pass));
		/*/

		break;
	}
}
//

?>