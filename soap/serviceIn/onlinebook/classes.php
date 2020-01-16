<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'req',
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
	'class',
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
	'classes',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:class[]')
	),
	'tns:class'
);

$server->wsdl->addComplexType(
	'resp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'classes' => array('name' => 'classes', 'type' => 'tns:classes'),
	)
);
//регистрация метода
$server->register("classes",
    array("response" => "tns:req"),
    array("return" => "tns:resp"),
    "urn:API",
    "urn:API#classes",
    "rpc",
    "encoded",
    "gets classes"
);
?>