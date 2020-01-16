<?php 
//формирование запроса
$server->wsdl->addComplexType(
	'servpr',
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
	'servspr',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:servpr[]')
	),
	'tns:servpr'
);

$server->wsdl->addComplexType(
	'serprReq',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'auth' => array('name' => 'auth', 'type' => 'tns:string'),
		'clid' => array('name' => 'clid', 'type' => 'tns:int'),
		'phone' => array('name' => 'phone', 'type' => 'tns:string', 'minOccurs' => 0),
		'mail' => array('name' => 'mail', 'type' => 'tns:string', 'minOccurs' => 0),
		'model' => array('name' => 'model', 'type' => 'tns:int', 'minOccurs' => 1),
		'disc' => array('name' => 'disc', 'type' => 'tns:int', 'minOccurs' => 1),
		'servspr' => array('name' => 'servspr', 'type' => 'tns:servspr'),
	)
);
//формирование ответа
$server->wsdl->addComplexType(
	'servprout',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'id' => array('name' => 'id', 'type' => 'xsd:int'),
		'name' => array('name' => 'name', 'type' => 'xsd:string'),
		'price' => array('name' => 'price', 'type' => 'xsd:string'),
		'last' => array('name' => 'last', 'type' => 'xsd:string'),
	)
);
$server->wsdl->addComplexType(
	'servsprout',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:servprout[]')
	),
	'tns:servprout'
);
$server->wsdl->addComplexType(
	'serprResp',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'state' => array ('name' => 'state', 'type' => 'xsd:int'),
		'msg' => array('name' => 'msg', 'type' => 'xsd:string'),
		'clid' => array('name' => 'clid', 'type' => 'xsd:int'),
		'servsprout' => array('name' => 'servsprout', 'type' => 'tns:servsprout'),
	)
);
//регистрация метода
$server->register("servpr",
    array("response" => "tns:serprReq"),
    array("return" => "tns:serprResp"),
    "urn:API",
    "urn:API#servpr",
    "rpc",
    "encoded",
    "get ser pr for user"
);
?>