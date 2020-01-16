<?php
function validateEmailMyOwn($value) {
	$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
	if (preg_match($pattern, $value) === 1) {
		$res = true;
	} else {
		$res = false;
	}
	return $res;
}
function validateNumberMyOwn($value) {
    $formats = array(
        '+#(###) ###-####'
    );
	$format = trim(preg_replace('/[0-9]/', '#', $value));
    $res = (in_array($format, $formats)) ? true : false;
	return $res;
}
include('func/auth.php');
include('func/cart.php');
include('func/item.php');
include('func/getCart.php');
include('func/clearCart.php');
include('func/sendCart.php');
include('func/orderInf.php');
include('func/onlinebook/tblData.php');
include('func/onlinebook/classes.php');
include('func/onlinebook/carCat.php');
include('func/onlinebook/marks.php');
include('func/onlinebook/models.php');
include('func/onlinebook/citys.php');
include('func/onlinebook/carwashes.php');
include('func/onlinebook/serTypes.php');
include('func/onlinebook/boxes.php');
include('func/onlinebook/clients.php');
include('func/onlinebook/f_req.php');
include('func/onlinebook/ser_pr.php');
include('func/onlinebook/servs.php');
include('func/onlinebook/l_req.php');
include('func/onlinebook/avtime.php');
include('func/onlinebook/slider.php');
include('func/onlinebook/gal.php');
include('func/onlinebook/social.php');
include('func/onlinebook/prices.php');
include('func/onlinebook/recList.php');
include('func/onlinebook/recData.php');
?>
