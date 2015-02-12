<?php
require_once '_admin.php';

if ($id != NULL) {
	$product = $admin->selectProductById($id);
?>
<h1>Edit product</h1>
<hr>
<form name="update" method="POST" enctype="multipart/form-data" action="popup.php?id=<?=$id ?>">
	<div>product id: <?=$product['id'] ?></div>
	
	<div>category id</div>
	<input type="input" name="category_id" value="<?=$product['category_id'] ?>">
	<div>title</div>
	<input type="input" name="title" value="<?=$product['title'] ?>">
	<div>description</div>
	<textarea name="description"><?=htmlspecialchars($product['description'])?></textarea>
	<div>price</div>
	<input type="input" name="price" value="<?=$product['price'] ?>">
	<div>image</div>
	<input type="file" name="image">
	<div><img src="<?=$product['image'] ?>"></div>
	<div><br></div>
	<input type="submit" name="action" value="update">
</form>
<?
}
else{
?>
<h1>Add new product</h1>
<hr>
<form name="add" method="POST" enctype="multipart/form-data"  action="admin.php">
	<div>category id</div>
	<input type="input" name="category_id" value="">
	<div>title</div>
	<input type="input" name="title" value="">
	<div>description</div>
	<textarea name="description"></textarea>
	<div>price</div>
	<input type="input" name="price" value="">
	<div>image</div>
	<input type="file" name="image">
	<div><img src=""></div>
	<div><br></div>
	<input type="submit" name="action" value="add">
</form>
<?
}
?>