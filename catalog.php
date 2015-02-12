<?php

require_once '_common.php';

$products = $shop->selectProducts();
if ($action = "sort_price") {
	$products = $shop->selectProductsByPrice($order);
}
?>
<h1>Products</h1>
<hr>
<table>
	<tr>
		<td>product id</td>
		<td>category id</td>
		<td>title</td>
		<td>description</td>
		<td>sort by price<br>
			<a href="catalog.php?action=sort_price&order=ASC">ASC</a>/<a href="catalog.php?action=sort_price&order=DESC">DESC</a></td>
		<td>image</td>
		<td></td>
	</tr>
	<? foreach ($products as $item) : ?>			
		<tr>
			<td><?=$item['id']?></td>
			<td><?=$item['category_id']?></td>
			<td><a href="detail.php?id=<?=$item['id']?>"><?=htmlspecialchars($item['title'])?></a></td>
			<td><?=htmlspecialchars($item['description'])?></td>
			<td><?=$item['price']?></td>
			<td><img src="./<?=$item['image']?>"></td>
			<td><a href="catalog.php?action=add&id=<?=$item['id']?>">add to cart</a></td>
		</tr>
	<? endforeach ?>
</table>
<p><a target="_blank" href="cart.php">Go to cart</a></p>
