<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'reqCitys',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string'),
		'page' => array('name' => 'page', 'type' => 'tns:int', 'minOccurs' => 1),
		'param' => array('name' => 'param', 'type' => 'tns:string'),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'city',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'reg' => array('name' => 'reg', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'citys',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:city[]')
	),
	'tns:city'
);

$server->wsdl->addComplexType(
	'respCitys',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'total' => array('name' => 'total', 'type' => 'xsd:int'),
		'citys' => array('name' => 'citys', 'type' => 'tns:citys'),
	)
);
//регистрация метода
$server->register("citys",
    array("response" => "tns:reqCitys"),
    array("return" => "tns:respCitys"),
    "urn:API",
    "urn:API#citys",
    "rpc",
    "encoded",
    "gets citys"
);
?>