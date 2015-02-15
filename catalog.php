<?php
require_once '_common.php';
require_once 'header.php';


$products = $shop->selectProducts();
if ($action = "sort_price") {
	$products = $shop->selectProductsByPrice($order);
}
?>
<div id="content">
<h1>Products</h1>
<hr>
<table>
	<tr>
		<td rowspan="2">product id</td>
		<td rowspan="2">category id</td>
		<td rowspan="2">title</td>
		<td rowspan="2">description</td>
		<td>sort by price</td>
		<td rowspan="2">image</td>
		<td rowspan="2"><a target="_blank" href="cart.php">Go to cart</a></td>
	</tr>
	<tr><td><a href="catalog.php?action=sort_price&order=ASC">ASC</a>/<a href="catalog.php?action=sort_price&order=DESC">DESC</a></td></tr>
	<? foreach ($products as $item) : ?>			
		<tr>
			<td><?=$item['id']?></td>
			<td><?=$item['category_id']?></td>
			<td><a href="detail.php?id=<?=$item['id']?>"><?=htmlspecialchars($item['title'])?></a></td>
			<td><?=htmlspecialchars($item['description'])?></td>
			<td><?=$item['price']?></td>
			<td><img class="product" src="./<?=$item['image']?>"></td>
			<td><a class="cart" href="catalog.php?action=add&id=<?=$item['id']?>" title="add to cart"></a></td>
		</tr>
	<? endforeach ?>
</table>
</div>
<?
require_once 'footer.php';
?>