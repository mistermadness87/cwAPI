<?php
session_start();
ini_set('soap.wsdl_cache_enabled', '0'); 
error_reporting(E_ALL);
ini_set('display_errors', '1');

$client = new SoapClient("https://topavenue.ru/cms/soap/service.php?wsdl", array('trace' => 1, 'encoding'=>'ISO-8859-1'));

/*
$orderreq = array(
	'auth' => $auth,
	'orderid' => '12210'
);
*/

$auth = array(
	'login' => 'mistermadness@ya.ru',
	'password' => 'a1234567',
);

//запрос пробивка товара
$parItem = array(
	'auth' => $auth,
	'itemid' => 608
);

//запрос добавление\правка\удаление корзины
$items = array(
	array('id' => 64864, 'qty' => 1, 'cartid' => '100'),
	array('id' => 64871, 'qty' => 5, 'cartid' => '100'),
	array('id' => 64870, 'qty' => 10, 'cartid' => '100'),
	array('id' => 64869, 'qty' => 10, 'cartid' => '100'),
	array('id' => 64868, 'qty' => 10, 'cartid' => '100'),
	array('id' => 64867, 'qty' => 2, 'cartid' => '100'),
	array('id' => 64865, 'qty' => 5, 'cartid' => '100'),
	array('id' => 64864, 'qty' => 5, 'cartid' => '100'),
	array('id' => 64862, 'qty' => 0, 'cartid' => '100'),
	array('id' => 64833, 'qty' => 0, 'cartid' => '100'),
	array('id' => 64832, 'qty' => 0, 'cartid' => '100'),
	array('id' => 64830, 'qty' => 0, 'cartid' => '100'),
	array('id' => 64829, 'qty' => 0, 'cartid' => '100'),
);
$par = array(
	'auth' => $auth,
	'items' => $items
);

//запрос содержимого корзины, очистка корзины
$cartids = array(
	array('id' => '101')
);
$parGetCart = array(
	'auth' => $auth,
	'cartids' => $cartids
);

//запрос для отправки заказа
$parOrder = array(
	'auth' => $auth,
	'cartid' => '101',
	'cuscont' => array(
		'name' => 'Дмитрий', 
		'phone' => '+7(916) 411-9460', 
		'mail' => 'mistermadness@ya.ru',
	),
	'address' => array(
		'index' => '125789',
		'city' => 'Москва',
		'street' => 'Вучетича',
		'house' => '10',
		'pod' => '3',
		'etazh' => '3',
		'domo' => '30в',
		'flat' => '30',
	),
	'ordPar' => array(
		'tk' => 0,
		'punkt' => 0,
		'delNames' => 0,
		'delType' => 16,
		'payType' => 1,
		'self' => 1,
		'timeDel' => 1,
		'delDate' => '31.10.2017',
	),
);
//$method = 'item';
$method = 'cart';
//$method = 'getCart';
//$method = 'clearCart';
//$method = 'sendCart';
//$method = 'orderInfo';
try {
	//$response = $client->$method($parItem);
	$response = $client->$method($par);
	//$response = $client->$method($parGetCart);
	//
	//$response = $client->$method($parOrder);
	echo '<pre>';
	print_r($response);
	echo '</pre>';
} catch(SoapFault $sf){
	print_r($sf);
}
?>