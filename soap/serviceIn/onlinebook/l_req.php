<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'serv',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'qty' => array('name' => 'qty', 'type' => 'xsd:int'),
	)
);
$server->wsdl->addComplexType(
	'servs',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:serv[]')
	),
	'tns:serv'
);

$server->wsdl->addComplexType(
	'longReq',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string'),
		'clid' => array('name' => 'clid', 'type' => 'tns:int', 'minOccurs' => 0),
		'name' => array('name' => 'name', 'type' => 'tns:string', 'minOccurs' => 1),
		'phone' => array('name' => 'param', 'type' => 'tns:string', 'minOccurs' => 1),
		'mail' => array('name' => 'mail', 'type' => 'tns:string', 'minOccurs' => 1),
		'marka' => array('name' => 'marka', 'type' => 'tns:int', 'minOccurs' => 1),
		'model' => array('name' => 'model', 'type' => 'tns:int', 'minOccurs' => 1),
		'gosnomer' => array('name' => 'gosnomer', 'type' => 'tns:string', 'minOccurs' => 0),
		'carwash' => array('name' => 'carwash', 'type' => 'tns:int', 'minOccurs' => 1),
		'box' => array('name' => 'box', 'type' => 'tns:int', 'minOccurs' => 1),
		'date' => array('name' => 'date', 'type' => 'tns:string', 'minOccurs' => 1),
		'time' => array('name' => 'time', 'type' => 'tns:string', 'minOccurs' => 1),
		'subscr' => array('name' => 'subscr', 'type' => 'tns:int', 'minOccurs' => 0),
		'recall' => array('name' => 'recall', 'type' => 'tns:int', 'minOccurs' => 0),
		'pushtp' => array('name' => 'pushtp', 'type' => 'tns:int', 'minOccurs' => 1),
		'sertype' => array('name' => 'sertype', 'type' => 'tns:int', 'minOccurs' => 1),
		'disc' => array('name' => 'disc', 'type' => 'tns:int', 'minOccurs' => 1),
		'servs' => array('name' => 'servs', 'type' => 'tns:servs', 'minOccurs' => 1),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'longResp',
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
$server->register("longrec",
    array("response" => "tns:longReq"),
    array("return" => "tns:longResp"),
    "urn:API",
    "urn:API#longrec",
    "rpc",
    "encoded",
    "creates full record"
);
?>