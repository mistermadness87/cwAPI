<?php
session_start();
ini_set('soap.wsdl_cache_enabled', '0'); 
ini_set('soap.wsdl_cache_ttl', '0');
include('../conf.php');

$password = "tutu";
$hostname = "localhost";

include('functions.php');
require_once "lib/nusoap.php";
require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/ostat/ostat.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/cms/priceClass/price.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/show/show.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/cart/cart.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/tables/table.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/rates.php');

$server = new soap_server();
$server->configureWSDL("API", "urn:API");

/* авторизация */
$server->wsdl->addComplexType(
	'auth',
	'complexType',
	'struct',
	'all',
	'',
	array(
		'login' => array('name' => 'login', 'type' => 'xsd:string', 'nillable' => 'true'),
		'password' => array('name' => 'password', 'type' => 'xsd:string', 'nillable' => 'true')
	)
);
/*
require_once 'serviceIn/item.php';
require_once 'serviceIn/cart.php';
require_once 'serviceIn/getCart.php';
require_once 'serviceIn/clearCart.php';
require_once 'serviceIn/sendCart.php';
require_once 'serviceIn/orderInf.php';
*/
/* он-лайн запись */
require_once 'serviceIn/onlinebook/tblData.php';
require_once 'serviceIn/onlinebook/classes.php';
require_once 'serviceIn/onlinebook/carCat.php';
require_once 'serviceIn/onlinebook/marks.php';
require_once 'serviceIn/onlinebook/models.php';
require_once 'serviceIn/onlinebook/citys.php';
require_once 'serviceIn/onlinebook/carwashes.php';
require_once 'serviceIn/onlinebook/serTypes.php';
require_once 'serviceIn/onlinebook/serCats.php';
require_once 'serviceIn/onlinebook/boxes.php';
require_once 'serviceIn/onlinebook/clients.php';
require_once 'serviceIn/onlinebook/f_req.php';
require_once 'serviceIn/onlinebook/l_req.php';
require_once 'serviceIn/onlinebook/ser_pr.php';
require_once 'serviceIn/onlinebook/servs.php';
require_once 'serviceIn/onlinebook/avtm.php';
require_once 'serviceIn/onlinebook/slider.php';
require_once 'serviceIn/onlinebook/gal.php';
require_once 'serviceIn/onlinebook/social.php';
require_once 'serviceIn/onlinebook/prices.php';
require_once 'serviceIn/onlinebook/recList.php';
require_once 'serviceIn/onlinebook/recData.php';

$post = file_get_contents('php://input');
$server->service($post);
?>