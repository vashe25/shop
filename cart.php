<?php
require_once '_common.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : NULL;
$order = isset($_GET['order']) ? $_GET['order'] : NULL;

switch ($action) {
	case 'delete':
		if ($goods[$id] > 1) {
			$goods[$id]--;
		}
		else {
			unset($goods[$id]);
		}
		$_SESSION['goods'] = $goods;
		header('Location: cart.php');
		break;

	case 'clearcart':
		unset($_SESSION['goods']);
		unset($mailSend);
		header('Location: cart.php');
		break;

	case 'order':
		$orderPrint = $shop->orderPrint($goods, session_id());
		$mailSend = $shop->mailSend($orderPrint);
		header('Location: cart.php');
		break;
}

$items = 0;
$cost = 0;

//проверим есть ли у нас массив с товарами, которые добавил пользователь в корзину
if (isset($_SESSION['goods'])) {
	
	//соберем супер-массив для проброски в шаблон вывода
	//выбираем ключи из массива: 'ID продукта' => 'количество штук'
	$goodsIds = array_keys($_SESSION['goods']);

	foreach ($goodsIds as &$element) {
		$element = intval($element);
	}
	
	//разделяем запятыми: 'ID продукта','ID продукта','ID продукта'...
	$goodsIdsComma = implode(',', $goodsIds);
	
	//выбираем из базы товары по 'ID продукта'
	$sql = "SELECT * FROM products WHERE id IN($goodsIdsComma)";
	$res = $db->prepare($sql);
	$res->execute();
	$product = $res->fetchAll();

	//отключаемся от базы данных
	$db = NULL;

	//добавляем в массив $продукта строку о количестве штук, заказанных пользователем
	foreach ($product as $key => $row) {
		$item = $_SESSION['goods'][$row['id']];
		$product[$key]['item'] = $item;
	}

	//посчитаем пользователю его заказ
	foreach ($product as $value) {
		
		//считаем количество товаров
		$items = $value['item'] + $items;
		
		//считаем общую стоимость заказа
		$cost = $value['item'] * $value['price'] + $cost;
		
	}
	//echo "<pre>";
	//var_dump($goodsIds);
	//пробрасываем в шаблон вывода супер-массив и посчитанные: количество и стоимость заказа
	echo $twig->render('cart.twig', array('product' => $product, 'items' => $items, 'cost' => $cost));
}

else {
	
	//запускаем шаблон, в случае пустой корзины
	echo $twig->render('cart.twig');
}
?>	