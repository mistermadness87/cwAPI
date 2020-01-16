<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'servsReq',
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
	'servout',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'tip' => array('name' => 'tip', 'type' => 'xsd:string'),
	)
);
$server->wsdl->addComplexType(
	'servouts',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:servout[]')
	),
	'tns:servout'
);
$server->wsdl->addComplexType(
	'servsResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'servouts' => array('name' => 'servouts', 'type' => 'tns:servouts'),
	)
);
//регистрация метода
$server->register("servs",
    array("response" => "tns:servsReq"),
    array("return" => "tns:servsResp"),
    "urn:API",
    "urn:API#servs",
    "rpc",
    "encoded",
    "get services"
);
?>