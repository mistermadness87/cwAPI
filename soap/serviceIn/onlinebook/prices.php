<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'priceReq',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string'),
		'param' => array('name' => 'param', 'type' => 'tns:string'),
		'model' => array('name' => 'model', 'type' => 'tns:int', 'minOccurs' => 0),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'priceel',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'kod' => array('name' => 'kod', 'type' => 'xsd:string'),
		'cena' => array('name' => 'cena', 'type' => 'xsd:string'),
		'prodolzhitelnost' => array('name' => 'prodolzhitelnost', 'type' => 'xsd:string'),
		'klass' => array('name' => 'klass', 'type' => 'xsd:string'),
		'typeName' => array('name' => 'typeName', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'priceels',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:priceel[]')
	),
	'tns:priceel'
);

$server->wsdl->addComplexType(
	'priceResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'priceels' => array('name' => 'priceels', 'type' => 'tns:priceels'),
	)
);
//регистрация метода
$server->register("price",
    array("response" => "tns:priceReq"),
    array("return" => "tns:priceResp"),
    "urn:API",
    "urn:API#price",
    "rpc",
    "encoded",
    "gets prices"
);
?>