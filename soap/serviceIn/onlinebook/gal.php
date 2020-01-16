<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'galReq',
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
	'galit',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'image' => array('name' => 'image', 'type' => 'xsd:string'),
		'txt' => array('name' => 'txt', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'galits',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:galit[]')
	),
	'tns:galit'
);

$server->wsdl->addComplexType(
	'galResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'galits' => array('name' => 'galits', 'type' => 'tns:galits'),
	)
);
//регистрация метода
$server->register("gal",
    array("response" => "tns:galReq"),
    array("return" => "tns:galResp"),
    "urn:API",
    "urn:API#gal",
    "rpc",
    "encoded",
    "gets gallery images"
);
?>