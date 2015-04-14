<?php
require_once '_admin.php';

//id determin
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

//action determin
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : NULL;

if ($action == 'delete') {
	$query = "DELETE FROM products WHERE id = :id";
		$product = $db->prepare($query);
		$product->exec(array(':id' => $id));
		header('Location: admin.php');
}

$products = $db->query("SELECT * FROM products");
$db = NULL;

echo $twig->render('admin.twig', array('products' => $products));
?>