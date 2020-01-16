<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'serv',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'qty' => array('name' => 'qty', 'type' => 'xsd:int'),
	)
);
$server->wsdl->addComplexType(
	'servs',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:serv[]')
	),
	'tns:serv'
);

$server->wsdl->addComplexType(
	'avtmReq',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string', 'minOccurs' => 1),
		'marka' => array('name' => 'marka', 'type' => 'tns:int', 'minOccurs' => 1),
		'model' => array('name' => 'model', 'type' => 'tns:int', 'minOccurs' => 1),
		'carwash' => array('name' => 'carwash', 'type' => 'tns:int', 'minOccurs' => 1),
		'box' => array('name' => 'box', 'type' => 'tns:int', 'minOccurs' => 0),
		'sertype' => array('name' => 'sertype', 'type' => 'tns:int', 'minOccurs' => 1),
		'date' => array('name' => 'date', 'type' => 'tns:string', 'minOccurs' => 1),
		'servs' => array('name' => 'servs', 'type' => 'tns:servs', 'minOccurs' => 1),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'tmm',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'val' => array('name' => 'val', 'type' => 'xsd:string'),
	)
);
$server->wsdl->addComplexType(
	'times',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:tmm[]')
	),
	'tns:tmm'
);

$server->wsdl->addComplexType(
	'avtmResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'times' => array('name' => 'times', 'type' => 'tns:times'),
	)
);
//регистрация метода
$server->register("avtime",
    array("response" => "tns:avtmReq"),
    array("return" => "tns:avtmResp"),
    "urn:API",
    "urn:API#avtime",
    "rpc",
    "encoded",
    "gets available time of date"
);
?>