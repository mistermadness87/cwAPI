<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'reqCls',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:auth'),
		'param' => array('name' => 'param', 'type' => 'tns:string'),
	)
);
//формирование ответа
/*
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
*/
$server->wsdl->addComplexType(
	'client',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'hash' => array('name' => 'hash', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'clients',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:client[]')
	),
	'tns:client'
);

$server->wsdl->addComplexType(
	'respCls',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'clients' => array('name' => 'clients', 'type' => 'tns:clients'),
	)
);
//регистрация метода
$server->register("clients",
    array("response" => "tns:reqCls"),
    array("return" => "tns:respCls"),
    "urn:API",
    "urn:API#clients",
    "rpc",
    "encoded",
    "gets clients"
);
?>