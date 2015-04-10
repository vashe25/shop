<?php
require_once '_admin.php';

//id determin
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($id != 0) {
	$row = $db->prepare("SELECT * FROM products WHERE id=:id");
	$row->execute(array(':id' => $id));
	$product = $row->fetchAll();
	$db = NULL;
	echo $twig->render('popup.twig', array('product' => $product[0], 'id' => $id));	
}
else{
	echo $twig->render('popup.twig');
}


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
?>