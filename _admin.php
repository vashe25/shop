<?php

class adminClass {
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
		$product = array();
		$get_product = "SELECT * FROM products WHERE id=$id";
		$row = mysql_query($get_product) or die(mysql_error());
		$product= mysql_fetch_assoc($row);
		return $product;
	}

	public function Upload($file) {
		$uploadDir = 'prod_pix/';
		$uploadedFile = $uploadDir. basename($_FILES['image']['name']);
		if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
		}
		else {
			$uploadedFile = $file;
		}
		return $uploadedFile;
	}

	public function filterNcombine ($catId, $ttle, $desc, $prce, $img) {
		/* filtering */
		$category_id = mysql_real_escape_string($catId);
		$title = mysql_real_escape_string($ttle);
		$description = mysql_real_escape_string($desc);
		$price = mysql_real_escape_string($prce);
		/* combine */
		$data = "category_id = '{$category_id}', title = '{$title}', description = '{$description}', price = '{$price}', image = '{$img}'";
		return $data;
	}
}
$admin = new adminClass();

//стартуем!
require_once 'vendor/autoload.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader);

//подключаемся к базе данных
$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

$db = new PDO('mysql:host=localhost;dbname=my','root', '', $opt);
