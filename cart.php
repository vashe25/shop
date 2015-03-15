<?php
require_once '_common.php';


//проверим есть ли у нас массив с товарами, которые добавил пользователь в корзину
if (isset($_SESSION['goods'])) {
	
	//достаем из сесии массив: 'ID продукта' => 'количество штук'
	$products = $shop->selectCartProducts();

	//соберем супер-массив для проброски в шаблон вывода
	foreach ($products as $key => $item) {
		//выбираем из базы массив $продукта по его ID
		$array =  $shop->selectProductById($key);
		//добавляем в массив $продукта строку о количестве штук, заказанных пользователем
		$array['item'] = $item;
		//собираем все массивы $продуктов в один супер-массив, где ключем является ID продукта
		$product["$key"]= $array;
	}
	
	//посчитаем пользователю его заказ
	foreach ($product as $value) {
		//считаем количество товаров
		$items = $value['item'] + $items;
		//считаем общую стоимость заказа
		$cost = $value['item'] * $value['price'] + $cost;
		
	}
	
	//пробрасываем в шаблон вывода супер-массив и посчитанные: количество и стоимость заказа
	echo $twig->render('cart.twig', array('product' => $product, 'items' => $items, 'cost' => $cost));
}

else {
	//запускаем шаблон, в случае пустой корзины
	echo $twig->render('cart.twig');
}
?>	