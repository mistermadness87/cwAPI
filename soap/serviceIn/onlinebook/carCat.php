<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'reqCarCat',
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
	'carCat',
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
	'carCats',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:carCat[]')
	),
	'tns:carCat'
);

$server->wsdl->addComplexType(
	'respCarCat',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'carCats' => array('name' => 'carCats', 'type' => 'tns:carCats'),
	)
);
//регистрация метода
$server->register("carCat",
    array("response" => "tns:reqCarCat"),
    array("return" => "tns:respCarCat"),
    "urn:API",
    "urn:API#carCats",
    "rpc",
    "encoded",
    "gets car category"
);
?>