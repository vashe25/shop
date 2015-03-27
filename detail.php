<?php
require_once '_common.php';

//определяем действие: добавление товара в корзину
$action = isset($_GET['action']) ? $_GET['action'] : NULL;
$id = isset($_GET['id']) ? $_GET['id'] : 0;

//проверяем факт добавления товара в корзину
if ($action == 'add') {
	//товар с таким ID уже есть в корзине
	if ($goods[$id] > 1) {
		//то увеличиваем количество штук
		$goods[$id]++;
	}
	//иначе, кладем одну штуку в корзину
	else {
		$goods[$id] = 1;
	}
	//присваиваем полученные данные в сессию
	$_SESSION['goods'] = $goods;
	header('Location: catalog.php');
}

//выбираем из базы данные о продукте с ID
$product = $db->query("SELECT * FROM products WHERE id=$id");
$db = NULL;
echo $twig->render('detail.twig', array('product' => $product));
?>