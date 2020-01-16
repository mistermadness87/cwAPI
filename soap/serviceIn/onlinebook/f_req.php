<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'fastReq',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string'),
		'name' => array('name' => 'name', 'type' => 'tns:string', 'minOccurs' => 1),
		'clid' => array('name' => 'clid', 'type' => 'tns:int'),
		'phone' => array('name' => 'param', 'type' => 'tns:string', 'minOccurs' => 1),
		'marka' => array('name' => 'marka', 'type' => 'tns:int', 'minOccurs' => 1),
		'model' => array('name' => 'model', 'type' => 'tns:int', 'minOccurs' => 1),
		'carwash' => array('name' => 'carwash', 'type' => 'tns:int', 'minOccurs' => 1),
		'date' => array('name' => 'date', 'type' => 'tns:string', 'minOccurs' => 1),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'fastResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'clid' => array('name' => 'clid', 'type' => 'xsd:int'),
	)
);
//регистрация метода
$server->register("fastrec",
    array("response" => "tns:fastReq"),
    array("return" => "tns:fastResp"),
    "urn:API",
    "urn:API#fastrec",
    "rpc",
    "encoded",
    "creates fast record"
);
?>