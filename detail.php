<?php
require_once '_common.php';

$product = $shop->selectProductById($id);
?>
<h1><?=$product['title']?></h1>
<img src="<?=$product['image']?>">
<h3>Category</h3>
<p><?=$product['category_id']?></p>
<h3>Description</h3>
<p><?=$product['description']?></p>
<h3>Price</h3>
<p><?=$product['price']?></p>