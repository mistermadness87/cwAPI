<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'reqBoxes',
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
	'sertype',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'name' => array('name' => 'name', 'type' => 'xsd:string')
	)
);
$server->wsdl->addComplexType(
	'sertypes',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:sertype[]')
	),
	'tns:sertype'
);

$server->wsdl->addComplexType(
	'box',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'carwash' => array('name' => 'carwash', 'type' => 'xsd:string'),
		'otkrytie' => array('name' => 'otkrytie', 'type' => 'xsd:string'),
		'zakrytie' => array('name' => 'zakrytie', 'type' => 'xsd:string'),
		'ser' => array('name' => 'ser', 'type' => 'tns:sertypes'),
	)
);

$server->wsdl->addComplexType(
	'boxes',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:box[]')
	),
	'tns:box'
);

$server->wsdl->addComplexType(
	'respBoxes',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'boxes' => array('name' => 'boxes', 'type' => 'tns:boxes'),
	)
);
//регистрация метода
$server->register("boxes",
    array("response" => "tns:reqBoxes"),
    array("return" => "tns:respBoxes"),
    "urn:API",
    "urn:API#boxes",
    "rpc",
    "encoded",
    "gets boxes"
);
?>