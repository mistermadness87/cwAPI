<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'reqCW',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string'),
		'param' => array('name' => 'param', 'type' => 'tns:string'),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'carwash',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'city' => array('name' => 'city', 'type' => 'xsd:string'),
		'adres' => array('name' => 'adres', 'type' => 'xsd:string'),
		'kordinata_x' => array('name' => 'kordinata_x', 'type' => 'xsd:string'),
		'kordinata_y' => array('name' => 'kordinata_y', 'type' => 'xsd:string'),
		'otkryvaetsya' => array('name' => 'otkryvaetsya', 'type' => 'xsd:string'),
		'zakryvaetsya' => array('name' => 'zakryvaetsya', 'type' => 'xsd:string'),
		'num' => array('name' => 'num', 'type' => 'xsd:int'),
	)
);

$server->wsdl->addComplexType(
	'carwashes',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:carwash[]')
	),
	'tns:carwash'
);

$server->wsdl->addComplexType(
	'respCW',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'carwashes' => array('name' => 'carwashes', 'type' => 'tns:carwashes'),
	)
);
//регистрация метода
$server->register("carwashes",
    array("response" => "tns:reqCW"),
    array("return" => "tns:respCW"),
    "urn:API",
    "urn:API#carwashes",
    "rpc",
    "encoded",
    "gets carwashes"
);
?>