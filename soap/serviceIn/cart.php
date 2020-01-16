<?php 
/* добавление, изменение, удаление корзина */
//формирование запроса
$server->wsdl->addComplexType(
	'item',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'qty' => array('name' => 'qty', 'type' => 'xsd:int'),
		'cartid' => array('name' => 'cartid', 'type' => 'xsd:string'),
	)
);
$server->wsdl->addComplexType(
	'items',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:item[]')
	),
	'tns:item'
);
$server->wsdl->addComplexType(
	'req',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:auth'),
		'items' => array('name' => 'items', 'type' => 'tns:items'),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'cartitem',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'itemId' => array('name' => 'itemId', 'type' => 'xsd:int'),
		'cid' => array('name' => 'cid', 'type' => 'xsd:string'),
		'qty' => array('name' => 'qty', 'type' => 'xsd:int'),
		'qtyWas' => array('name' => 'qtyWas', 'type' => 'xsd:int'),
		'avaiErr' => array('name' => 'avaiErr', 'type' => 'xsd:int'),
		'price' => array('name' => 'price', 'type' => 'xsd:string'),
	)
);
$server->wsdl->addComplexType(
	'cartitems',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:cartitem[]')
	),
	'tns:cartitem'
);
$server->wsdl->addComplexType(
	'resp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'sum' => array ('name' => 'sum', 'type' => 'xsd:int'),
		'cartitems' => array('name' => 'cartitems', 'type' => 'tns:cartitems'),
	)
);
//регистрация метода
$server->register("cart",
    array("response" => "tns:req"),
    array("return" => "tns:resp"),
    "urn:API",
    "urn:API#cart",
    "rpc",
    "encoded",
    "Insert, Delete, Update Cart"
);
?>