<?php 
/* получение содержимого корзины */
//формирование запроса
$server->wsdl->addComplexType(
	'ordPar',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'punkt' => array('name' => 'punkt', 'type' => 'xsd:int'),
		'delNames' => array('name' => 'delNames', 'type' => 'xsd:int'),
		'delType' => array('name' => 'delType', 'type' => 'xsd:int'),
		'payType' => array('name' => 'payType', 'type' => 'xsd:int'),
		'self' => array('name' => 'self', 'type' => 'xsd:int'),
		'timeDel' => array('name' => 'timeDel', 'type' => 'xsd:int'),
		'delDate' => array('name' => 'delDate', 'type' => 'xsd:int'),
		'comm' => array('name' => 'comm', 'type' => 'xsd:string'),
	)
);
$server->wsdl->addComplexType(
	'cusCont',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'phone' => array('name' => 'phone', 'type' => 'xsd:string'),
		'mail' => array('name' => 'mail', 'type' => 'xsd:string'),
	)
);
$server->wsdl->addComplexType(
	'Address',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'index' => array('name' => 'index', 'type' => 'xsd:string', 'minOccurs' => 0),
		'city' => array('name' => 'city', 'type' => 'xsd:string'),
		'street' => array('name' => 'street', 'type' => 'xsd:string'),
		'house' => array('name' => 'house', 'type' => 'xsd:string'),
		'korp' => array('name' => 'korp', 'type' => 'xsd:string', 'minOccurs' => 0),
		'pod' => array('name' => 'pod', 'type' => 'xsd:string', 'minOccurs' => 0),
		'etazh' => array('name' => 'etazh', 'type' => 'xsd:string', 'minOccurs' => 0),
		'domo' => array('name' => 'domo', 'type' => 'xsd:string', 'minOccurs' => 0),
		'flat' => array('name' => 'flat', 'type' => 'xsd:string', 'minOccurs' => 0),
	)
);

$server->wsdl->addComplexType(
	'orderData',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:auth'),
		'cartid' => array('name' => 'cartid', 'type' => 'tns:string'),
		'cuscont' => array('name' => 'cuscont', 'type' => 'tns:cusCont'),
		'address' => array('name' => 'cuscont', 'type' => 'tns:Address'),
		'ordPar' => array('name' => 'ordPar', 'type' => 'tns:ordPar'),
		'prPred' => array('name' => 'prPred', 'type' => 'xsd:float', 'minOccurs' => 0),
		'ordType' => array('name' => 'ordType', 'type' => 'xsd:int', 'minOccurs' => 0),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'order',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'ordernum' => array('name' => 'ordernum', 'type' => 'xsd:string'),
	)
);
//регистрация метода
$server->register("sendCart",
    array("response" => "tns:orderData"),
    array("return" => "tns:order"),
    "urn:API",
    "urn:API#sendCart",
    "rpc",
    "encoded",
    "order"
);
?>