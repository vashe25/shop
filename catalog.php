<?php
require_once '_common.php';

//определяем действие: добавление товара в корзину
$action = isset($_GET['action']) ? $_GET['action'] : NULL;
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$order = isset($_GET['order']) ? $_GET['order'] : NULL;

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
		
//сортируем товары, если требуется
if ($action = "sort_price") {
	$products = $db->query("SELECT * FROM products ORDER BY price $order");
}
else {
	//выбираем все продукты из базы
	$products = $db->query("SELECT * FROM products");
}
//Закрываем соединение с базой данных
$db = NULL;

//отправляем данные в шаблон
echo $twig->render('catalog.twig', array('products' => $products));

?>