<?php
require_once '_common.php';

$products = $shop->selectCartProducts();
$product = array();
$items=0;
$cost=0;

if (isset($_SESSION['goods'])) {
?>
<h1>Your cart</h1>
<hr>
<table>
		<tr>
			<td>Product uniq id/title:</td>
			<td>Product count:</td>
			<td>Image</td>
			<td>Edit:</td>
		</tr>
	<? foreach ($products as $key => $item) : ?>
		<?$product=$shop->selectProductById($key);?>
		<tr>
			<td><?=$key?>/<?=$product["title"]?></td>
			<td>Amount: <?= htmlspecialchars($item); $items=$items+$item;?>
				<br>Cost: <?$cost=$item*$product["price"]+$cost; echo $product["price"]*$item;?> $</td>
			<td><img src="<?=$product['image']?>" /></td>
			<td><a href="cart.php?action=delete&id=<?=$key?>">delete</a></td>
		</tr>
	<? endforeach ?>
		<tr><td>Sum of items:</td><td><?=$items?></td><td></td><td></td></tr>
		<tr><td>Total cost:</td><td><?=$cost?> $</td><td></td><td><a href="cart.php?action=order">Send order!</a></td></tr>
		<tr><td></td><td><a href="cart.php?action=clearcart">Clear cart</a></td><td><a href="catalog.php">Back to catalog</a></td><td></td></tr>
</table>
<?
}
else {
	echo '<h2>No goods!</h2><h3><a href="catalog.php">Go to catalog</a></h3>';
}
?>