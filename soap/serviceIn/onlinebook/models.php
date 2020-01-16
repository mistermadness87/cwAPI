<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'reqModels',
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
	'model',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'marka' => array('name' => 'marka', 'type' => 'xsd:string'),
		'model' => array('name' => 'model', 'type' => 'xsd:string'),
		'class' => array('name' => 'class', 'type' => 'xsd:string'),
		'catName' => array('catName' => 'catName', 'type' => 'xsd:string'),
		'god_vypuska_ot' => array('god_vypuska_ot' => 'god_vypuska_ot', 'type' => 'xsd:string'),
		'god_vypuska_do' => array('god_vypuska_do' => 'god_vypuska_do', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'models',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:model[]')
	),
	'tns:model'
);

$server->wsdl->addComplexType(
	'respModels',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'models' => array('name' => 'models', 'type' => 'tns:models'),
	)
);
//регистрация метода
$server->register("models",
    array("response" => "tns:reqModels"),
    array("return" => "tns:respModels"),
    "urn:API",
    "urn:API#models",
    "rpc",
    "encoded",
    "gets car models"
);
?>