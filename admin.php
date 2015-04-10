<?php
require_once '_admin.php';

//id determin
$id = isset($_GET['id']) ? $_GET['id'] : 0;
//action determin
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : NULL;

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

$products = $db->query("SELECT * FROM products");
$db = NULL;

echo $twig->render('admin.twig', array('products' => $products));
?>