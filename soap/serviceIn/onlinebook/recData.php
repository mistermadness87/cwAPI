<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'recDataReq',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string'),
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'recSerList',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'code' => array('name' => 'code', 'type' => 'xsd:string'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'price' => array('name' => 'price', 'type' => 'xsd:string'),
		'qty' => array('name' => 'qty', 'type' => 'xsd:int'),
		'last' => array('name' => 'last', 'type' => 'xsd:int'),
	)
);

$server->wsdl->addComplexType(
	'recSerLists',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:recSerList[]')
	),
	'tns:recSerList'
);

$server->wsdl->addComplexType(
	'recDataResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'date' => array('name' => 'date', 'type' => 'xsd:string'),
		'carwash' => array('name' => 'carwash', 'type' => 'xsd:string'),
		'post' => array('name' => 'post', 'type' => 'xsd:string'),
		'client' => array('name' => 'client', 'type' => 'xsd:string'),
		'mail' => array('name' => 'mail', 'type' => 'xsd:string'),
		'phone' => array('name' => 'phone', 'type' => 'xsd:string'),
		'payt' => array('name' => 'payt', 'type' => 'xsd:string'),
		'disc' => array('name' => 'disc', 'type' => 'xsd:string'),
		'time' => array('name' => 'time', 'type' => 'xsd:string'),
		'comment' => array('name' => 'comment', 'type' => 'xsd:string'),
		'admin' => array('name' => 'admin', 'type' => 'xsd:string'),
		'car' => array('name' => 'car', 'type' => 'xsd:string'),
		'status' => array('name' => 'status', 'type' => 'xsd:string'),
		'recSerLists' => array('name' => 'recSerLists', 'type' => 'tns:recSerLists'),
	)
);
//регистрация метода
$server->register("recData",
    array("response" => "tns:recDataReq"),
    array("return" => "tns:recDataResp"),
    "urn:API",
    "urn:API#recList",
    "rpc",
    "encoded",
    "gets record data"
);
?>