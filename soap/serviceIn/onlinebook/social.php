<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'socReq',
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
	'socit',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'sylka' => array('name' => 'sylka', 'type' => 'xsd:string'),
		'znachok' => array('name' => 'znachok', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'socits',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:socit[]')
	),
	'tns:socit'
);

$server->wsdl->addComplexType(
	'socResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'socits' => array('name' => 'socits', 'type' => 'tns:socits'),
	)
);
//регистрация метода
$server->register("social",
    array("response" => "tns:socReq"),
    array("return" => "tns:socResp"),
    "urn:API",
    "urn:API#social",
    "rpc",
    "encoded",
    "gets social networks"
);
?>