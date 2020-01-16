<?php 
/* получение содержимого корзины */
//формирование запроса
$server->wsdl->addComplexType(
	'cartid',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:string')
	)
);
$server->wsdl->addComplexType(
	'cartids',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:cartid[]')
	),
	'tns:cartid'
);
$server->wsdl->addComplexType(
	'reqCart',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:auth'),
		'cartids' => array('name' => 'cartids', 'type' => 'tns:cartids'),
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
	'resCart',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'sum' => array ('name' => 'sum', 'type' => 'xsd:string'),
		'cartitems' => array('name' => 'cartitems', 'type' => 'tns:cartitems'),
	)
);
//регистрация метода
$server->register("getCart",
    array("response" => "tns:reqCart"),
    array("return" => "tns:resCart"),
    "urn:API",
    "urn:API#getCart",
    "rpc",
    "encoded",
    "Cart data"
);
?>