<?php
require_once '_admin.php';
$products = $admin->selectProducts();
?>
<h1>Shop->products DATAbase:</h1>
<hr>
<table>
	<tr>
		<td>product id</td>
		<td>category id</td>
		<td>title</td>
		<td>description</td>
		<td>price</td>
		<td>image</td>
		<td></td>
	</tr>
	<? foreach ($products as $item) : ?>			
		<tr>
			<td><?=$item['id']?></td>
			<td><?=$item['category_id']?></td>
			<td><?=htmlspecialchars($item['title'])?></td>
			<td><?=htmlspecialchars($item['description'])?></td>
			<td><?=$item['price']?></td>
			<td><img src="<?=$item['image']?>"></td>
			<td><a href="popup.php?id=<?=$item['id']?>">edit</a> / <a href="admin.php?action=delete&id=<?=$item['id']?>">delete</a></td>
		</tr>
	<? endforeach ?>
</table>
<p><a href="popup.php?action=add">Add new product</a></p>