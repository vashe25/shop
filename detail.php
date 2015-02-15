<?php
require_once '_common.php';
require_once 'header.php';


$product = $shop->selectProductById($id);
?>
<div id="content">
<h1><?=$product['title']?></h1>
<hr>

<img class="product" style="margin-right: 10px;" src="<?=$product['image']?>"><table style="display: inline-table;width:86%;">
	<tr><td>Category:</td><td><?=$product['category_id']?></td><td rowspan="3"><a class="cart" href="detail.php?action=add&id=<?=$product['id']?>" title="add to cart"></a></td></tr>
	<tr><td>Description:</td><td><?=$product['description']?></td></tr>
	<tr><td>Price:</td><td><?=$product['price']?></td></tr>
</table>
</div>
<?
require_once 'footer.php';
?>