<?php
require_once '_common.php';

$product = $shop->selectProductById($id);
echo $twig->render('detail.twig', array('product' => $product));
?>