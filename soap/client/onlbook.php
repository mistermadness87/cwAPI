<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);

$client = new SoapClient("https://crmsklad.ru/cms/soap/service.php?wsdl", array('trace' => 1, 'encoding'=>'ISO-8859-1'));

/*
$parArr = array(
	'marka' => 6
);
*/
/*
$auth = array(
	'login' => 'api_admin',
	'password' => '88d1ac2cb8449e83e4be19b47949c944',
);
*/

$auth = 'popo';

/*
$req = array(
	'auth' => 'popo',
	'page' => 1,
	'param' => $par,
);
*/

/*
$req = array(
	'auth' => 'popo',
	'name' => 'Дмитрий',
	'clid' => 2,
	'phone' => '+7(916) 411-9460',
	'marka' => 6,
	'model' => 79,
	'carwash' => 1,
	'date' => '19.08.2019',
);
*/
/*
$req = array(
	'auth' => 'popo',
	'clid' => 0,
	'param' => $par,
	'model' => 79,
	'disc' => 10,
	'servspr' => array(
		array(
			'id' => 1, 
			'qty' => 1
		),
		array(
			'id' => 2, 
			'qty' => 1
		),
	)
);
*/
/*
$req = array(
	'auth' => 'popo',
	'marka' => 6,
	'model' => 79,
	'carwash' => 1,
	'box' => 0,
	'sertype' => 0,
	'date' => '08.08.2019',
	'servs' => array(
		array(
			'id' => 1, 
			'qty' => 1
		),
		array(
			'id' => 2, 
			'qty' => 1
		),
	),
);
*/
/*
$req = array(
	'auth' => 'popo',
	'clid' => 2,
	'name' => 'Дмитрий',
	'phone' => '+7(916) 411-9460',
	'mail' => 'mistermadness@ya.ru',
	'marka' => 6,
	'model' => 79,
	'gosnomer' => '654',
	'carwash' => 1,
	'box' => 0,
	'date' => '08.08.2019',
	'time' => '11:30',
	'subscr' => 0,
	'recall' => 1,
	'pushtp' => 1,
	'sertype' => 1,
	'disc' => 10,
	'servs' => array(
		array(
			'id' => 1, 
			'qty' => 1
		),
		array(
			'id' => 2, 
			'qty' => 1
		),
	),
);
*/

//$method = 'classes';
//$method = 'carCat';
//$method = 'marks';
//$method = 'models';
//$method = 'citys';
//$method = 'carwashes';
//$method = 'serTypes';
//$method = 'boxes';
//$method = 'clients';
//$method = 'fastrec';
//$method = 'servs';
//$method = 'servpr';
//$method = 'avtime';
//$method = 'longrec';
/*
$parArr = array(
	'klass' => 1,
	'typeName' => 2
);

$par = http_build_query($parArr);

$req = array(
	'auth' => $auth,
	'param' => $par,
	'model' => 79
);

$method = 'price';
*/



$req = array(
	'auth' => $auth,
	'id' => 29
);

$method = 'recData';

$parArr = array(
	'klass' => 1,
	'typeName' => 2
);

$par = http_build_query($parArr);

$req = array(
	'auth' => $auth,
	'date1' => '01.01.2000',
	'date2' => '14.01.2020'
	//'client' => 0
);
/*
$method = 'recList';

echo '<pre>';
print_r($method);
echo '</pre>';

*/

$req = array(
	'auth' => 'popo',
	'param' => ''
);

echo '<pre>';
print_r($req);
echo '</pre>';
$method = 'servs';

try {
	$response = $client->$method($req);
	echo '<pre>';
	print_r($response);
	echo '</pre>';
} catch(SoapFault $sf){
	echo '<pre>';
	print_r($client->__getLastResponse());
	echo '</pre>';
}
?>