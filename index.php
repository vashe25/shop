<?php
print $_SERVER['REQUEST_URI'];

switch ($_SERVER['REQUEST_URI']) {
	case '/catalog/':
		print 'catalog.php';
		break;

	case '/cart/':
		print 'cart.php';
		break;
		
	case '/admin/':
		print 'admin.php';
		break;
	
	default:
		# code...
		break;
}
?>