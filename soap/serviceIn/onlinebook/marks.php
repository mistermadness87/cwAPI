<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'reqMarks',
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
	'mark',
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
	'marks',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:mark[]')
	),
	'tns:mark'
);

$server->wsdl->addComplexType(
	'respMarks',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'marks' => array('name' => 'marks', 'type' => 'tns:marks'),
	)
);
//регистрация метода
$server->register("marks",
    array("response" => "tns:reqMarks"),
    array("return" => "tns:respMarks"),
    "urn:API",
    "urn:API#marks",
    "rpc",
    "encoded",
    "gets car marks"
);
?>