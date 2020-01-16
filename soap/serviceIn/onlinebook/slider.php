<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'sliderReq',
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
	'slout',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'url' => array('name' => 'url', 'type' => 'xsd:string'),
		'photo' => array('name' => 'photo', 'type' => 'xsd:string'),
		'txt1' => array('name' => 'txt1', 'type' => 'xsd:string'),
		'txt2' => array('name' => 'txt2', 'type' => 'xsd:string'),
		'show' => array('name' => 'show', 'type' => 'xsd:string'),
	)
);
$server->wsdl->addComplexType(
	'slouts',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:slout[]')
	),
	'tns:slout'
);
$server->wsdl->addComplexType(
	'sliderResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'slouts' => array('name' => 'slouts', 'type' => 'tns:slouts'),
	)
);
//регистрация метода
$server->register("slider",
    array("response" => "tns:sliderReq"),
    array("return" => "tns:sliderResp"),
    "urn:API",
    "urn:API#slider",
    "rpc",
    "encoded",
    "get slider images"
);
?>