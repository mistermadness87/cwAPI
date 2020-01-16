<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'recListReq',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string'),
		'date1' => array('name' => 'date1', 'type' => 'tns:string'),
		'date2' => array('name' => 'date2', 'type' => 'tns:string'),
		'client' => array('name' => 'client', 'type' => 'xsd:int', 'minOccurs' => 0),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'recList',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'date' => array('name' => 'date', 'type' => 'xsd:string'),
		'client' => array('name' => 'client', 'type' => 'xsd:string'),
		'status' => array('name' => 'status', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'recLits',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:recList[]')
	),
	'tns:recList'
);

$server->wsdl->addComplexType(
	'recListResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'recLits' => array('name' => 'recLits', 'type' => 'tns:recLits'),
	)
);
//регистрация метода
$server->register("recList",
    array("response" => "tns:recListReq"),
    array("return" => "tns:recListResp"),
    "urn:API",
    "urn:API#recList",
    "rpc",
    "encoded",
    "gets records"
);
?>