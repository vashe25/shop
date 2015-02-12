<?php
//action determin
$action = $_REQUEST['action'];
//id determin
$id = $_REQUEST['id'];

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

switch ($action) {
	case 'update':
		/* upload */
		$product = $admin->selectProductById($id);
		$img = $admin->Upload($product['image']);
		/* filtered AND combined */
		$data = $admin->filterNcombine($_POST['category_id'], $_POST['title'], $_POST['description'], $_POST['price'], $img);
		$sqlupdate = "UPDATE products SET {$data} WHERE id = '{$id}'";
		mysql_query($sqlupdate) or die(mysql_error());
		break;
	
	case 'add':
		/* upload */
		$img = $admin->Upload();
		/* filtered AND combined */
		$data = $admin->filterNcombine($_POST['category_id'], $_POST['title'], $_POST['description'], $_POST['price'], $img);
		$sqladd = "INSERT INTO products SET {$data}";
		mysql_query($sqladd) or die(mysql_error());
		break;

	case 'delete':
		$sqldelete = "DELETE FROM products WHERE id = {$id}";
		mysql_query($sqldelete) or die(mysql_error());
		header('Location: admin.php');
		break;

	/*default:
		DELETE FROM `my`.`products` WHERE `products`.`id` = 40 AND `products`.`category_id` = 4 AND `products`.`title` = 'Product 4' AND `products`.`description` = 'Description of Product 4' AND `products`.`price` = 20 AND `products`.`image` = 'img.jpg' LIMIT 1;
		break;*/
}
