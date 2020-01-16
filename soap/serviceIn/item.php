<?php 
/* добавление, изменение, удаление корзина */
//формирование запроса
$server->wsdl->addComplexType(
	'reqItem',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:auth'),
		'itemid' => array('name' => 'itemid', 'type' => 'xsd:int'),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'resItem',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'stock' => array ('name' => 'stock', 'type' => 'xsd:string'),
		'price' => array ('name' => 'price', 'type' => 'xsd:int'),
		'price2' => array ('name' => 'price2', 'type' => 'xsd:int'),
	)
);
//регистрация метода
$server->register("item",
    array("response" => "tns:reqItem"),
    array("return" => "tns:resItem"),
    "urn:API",
    "urn:API#item",
    "rpc",
    "encoded",
    "item stock data"
);
?>