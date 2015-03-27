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
	
	//достаем из сесии массив: 'ID продукта' => 'количество штук'
	
	//соберем супер-массив для проброски в шаблон вывода
	$goodsIds = array_keys($_SESSION['goods']);
	
	$goodsIdsComma = implode(',', $goodsIds);
	$sql = "SELECT * FROM products WHERE id IN($goodsIdsComma)";
	$res = $db->query($sql);
	$rows = $res->fetchAll(PDO::FETCH_ASSOC);

	foreach ($rows as $key => $row) {
		$count = $_SESSION['goods'][$row['id']];
		$rows[$key]['count'] = $count;
	}

	print '<pre>';
	var_dump($_SESSION['goods']);
	var_dump($rows);

	//добавляем в массив $продукта строку о количестве штук, заказанных пользователем
	//$array['item'] = $item;
	//собираем все массивы $продуктов в один супер-массив, где ключем является ID продукта
	//$product["$key"]= $array;
	
	//посчитаем пользователю его заказ
	//foreach ($product as $value) {
		//считаем количество товаров
		//$items = $value['item'] + $items;
		//считаем общую стоимость заказа
		//$cost = $value['item'] * $value['price'] + $cost;
		
	//}
	
	var_dump($array);
	//пробрасываем в шаблон вывода супер-массив и посчитанные: количество и стоимость заказа
	//echo $twig->render('cart.twig', array('product' => $product, 'items' => $items, 'cost' => $cost));
}

else {
	//запускаем шаблон, в случае пустой корзины
	echo $twig->render('cart.twig');
}
?>	