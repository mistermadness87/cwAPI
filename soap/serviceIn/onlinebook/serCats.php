<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'reqSerCat',
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
	'sercatid',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'sercatids',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:sercatid[]')
	),
	'tns:sercatid'
);

$server->wsdl->addComplexType(
	'respSerCat',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'sercats' => array('name' => 'sercats', 'type' => 'tns:sercatids'),
	)
);
//регистрация метода
$server->register("sercats",
    array("response" => "tns:reqSerCat"),
    array("return" => "tns:respSerCat"),
    "urn:API",
    "urn:API#sercats",
    "rpc",
    "encoded",
    "gets sercategory"
);
?>