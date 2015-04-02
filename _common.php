<?php
require_once 'vendor/autoload.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

class ShopClass
{
	private $_db;

	public function __construct() {
		$connect = mysql_connect("localhost", "root", "") or die(mysql_error());
		mysql_select_db("my", $connect) or die(mysql_error());
	}

	public function selectProducts() {
		$products = array();
		$sql = "SELECT * FROM products";
		$cursor = mysql_query($sql) or die(mysql_error());
		while ($row = mysql_fetch_assoc($cursor)) {
			$products[] = $row;
		}
		return $products;
	}

	public function selectProductById($id) {
		$get_product = "SELECT * FROM products WHERE id=$id";
		$row = mysql_query($get_product) or die(mysql_error());
		$product= mysql_fetch_assoc($row);
		return $product;
	}

	public function selectProductsByPrice($order){
		$products = array();
		$sql = "SELECT * FROM products ORDER BY price $order";
		$cursor = mysql_query($sql) or die(mysql_error());
		while ($row = mysql_fetch_assoc($cursor)) {
			$products[] = $row;
		}
		return $products;
	}

	public function selectCartProducts() {
		$products = array();
		$products = $_SESSION ['goods'];
		return $products;
	}
	
	public function orderPrint ($goods, $sid) {
		$i=0;
		$result = "<p>User: <b>$sid</b></p>";
		$result .= "<h3>Order table</h3>";
		$result .= "<table><tr><td></td><td>Product id:</td><td>items:</td></tr>";
		foreach ($goods as $key => $value) {
			$item = "<tr><td>$i</td><td>$key</td><td>$value</td></tr>";
			$result .= $item;
			$i++;
		}
		$result .= "</table>";
		unset($i, $item);
		return $result;
	}

	public function mailSend($orderPrint) {
		$msg .= "<html><body><head><title>New order</title></head>";
		$msg .= $orderPrint;
		$msg .= "</body></html>";
		$sub = "Order_$sid";
		$to = 'vshevchenko@dalee.ru';
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$mailSend = mail($to, $sub, $msg, $headers);
		return $mailSend;
	}
}

$shop = new ShopClass();
//подключаемся к базе данных
$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

$db = new PDO('mysql:host=localhost;dbname=my','root', '', $opt);

//старт сессии
session_start();
$goods = isset($_SESSION['goods']) ? $_SESSION['goods'] : array();
