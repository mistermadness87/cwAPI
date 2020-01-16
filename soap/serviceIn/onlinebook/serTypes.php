<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'reqSert',
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
	'sertypeid',
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
	'sertypeids',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:sertypeid[]')
	),
	'tns:sertypeid'
);

$server->wsdl->addComplexType(
	'respSert',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'sertypes' => array('name' => 'sertypes', 'type' => 'tns:sertypeids'),
	)
);
//регистрация метода
$server->register("sertypes",
    array("response" => "tns:reqSert"),
    array("return" => "tns:respSert"),
    "urn:API",
    "urn:API#sertypes",
    "rpc",
    "encoded",
    "gets sertypes"
);
?>