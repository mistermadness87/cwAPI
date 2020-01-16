<?php 	
/* получение информации о заказе */
//формирование запроса
$server->wsdl->addComplexType(
	'reqOrder',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:auth'),
		'orderid' => array('name' => 'orderid', 'type' => 'xsd:string'),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'orderel',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'qty' => array('name' => 'qty', 'type' => 'xsd:string'),
		'qtysobr' => array('name' => 'qtysobr', 'type' => 'xsd:int'),
	)
);
$server->wsdl->addComplexType(
	'orderelarr',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:orderel[]')
	),
	'tns:orderel'
);
$server->wsdl->addComplexType(
	'resOrder',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'orderels' => array('name' => 'orderels', 'type' => 'tns:orderelarr'),
		'address' => array('name' => 'cuscont', 'type' => 'tns:Address'),
		'status' => array('name' => 'status', 'type' => 'xsd:int'),
		'cuscont' => array('name' => 'cuscont', 'type' => 'tns:cusCont'),
	)
);
//регистрация метода
$server->register("orderInfo",
    array("response" => "tns:reqOrder"),
    array("return" => "tns:resOrder"),
    "urn:API",
    "urn:API#orderInfo",
    "rpc",
    "encoded",
    "Order data"
);
?>