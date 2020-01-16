<?php 
/* добавление, изменение, удаление корзина */
//формирование запроса
$server->wsdl->addComplexType(
	'reqTbl',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string'),
		'tbl' => array('name' => 'tbl', 'type' => 'tns:string'),
		'param' => array('name' => 'param', 'type' => 'tns:string'),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'tblRow',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'data' => array('name' => 'data', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'tblRows',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:tblRow[]')
	),
	'tns:tblRow'
);

$server->wsdl->addComplexType(
	'respTbl',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'tblRows' => array('name' => 'tblRows', 'type' => 'xsd:string'),
		//'tblRows' => array('name' => 'tblRows', 'type' => 'tns:tblRows'),
	)
);
//регистрация метода
$server->register("tblData",
    array("response" => "tns:reqTbl"),
    array("return" => "tns:respTbl"),
    "urn:API",
    "urn:API#tblData",
    "rpc",
    "encoded",
    "gets table data"
);
?>