<?php
require_once '_admin.php';

//аплоад картинок
function Upload($file) {
	$uploadDir = 'prod_pix/';
	$uploadedFile = $uploadDir. basename($_FILES['image']['name']);
	if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
	}
	else {
		$uploadedFile = $file;
	}
	return $uploadedFile;
}

//id determin
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

//action determin
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : NULL;

switch ($action) {
	case 'update':
		//достаем из базы запись о картинке
		$query = "SELECT * FROM products WHERE id=:id";
		$product = $db->prepare($query);
		$product->execute(array(':id' => $id));
		$product = $product->fetchAll();
		
		//вставляем запись из базы если она есть, если нет, функция сама подставит путь к загруженному файлу
		$img = Upload($product['image']);
				
		//апдейт строки-продукта
		$query = "UPDATE products SET category_id = :category_id, title = :title, description = :description, price = :price, image = :img WHERE id = :id";
		$product = $db->prepare($query);
		$product->execute(array(':category_id' => $_POST['category_id'], ':title' => $_POST['title'], ':description' => $_POST['description'], ':price' => $_POST['price'], ':img' => $img, ':id' => $id));
		break;
	
	case 'add':
		//загружаем картинку
		$img = Upload('');
		//пишем в базу
		$query = "INSERT INTO products SET category_id = :category_id, title = :title, description = :description, price = :price, image = :img";
		$product = $db->prepare($query);
		$product->execute(array(':category_id' => $_POST['category_id'], ':title' => $_POST['title'], ':description' => $_POST['description'], ':price' => $_POST['price'], ':img' => $img));
		break;	
}

if ($id != 0) {
	$row = $db->prepare("SELECT * FROM products WHERE id=:id");
	$row->execute(array(':id' => $id));
	$product = $row->fetchAll();
	

	$db = NULL;
	$parameter = "popup.php?id=$product[0]['id']";
	$action = 'update';
	echo $twig->render('popup.twig', array('product' => $product[0], 'parameter' => $parameter, 'action' => $action));	
}
else{

	$action = 'add';
	$parameter = "admin.php";
	echo $twig->render('popup.twig', array('parameter' => $parameter, 'action' => $action));
}
?>