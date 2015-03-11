<?php
require_once '_common.php';

$products = $shop->selectProducts();
if ($action = "sort_price") {
	$products = $shop->selectProductsByPrice($order);
}

//$template = $twig->loadTemplate('catalog.twig');
//echo $template->render(array('products' => $products));
echo $twig->render('catalog.twig', array('products' => $products));

?>
