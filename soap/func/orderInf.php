<?php 
//получение содержимого корзины
function orderInfo($reqOrder){
	global $shop;
	$udata = auth($reqOrder['auth']['login'], $reqOrder['auth']['password']);
	$t = $udata[0];
	$userid = $udata[1];
	$top = $udata[2];
	$name = $udata[3];
	$orderels = array();
	$address = array();
	$cuscont = array();
	if($t==0){
		$arr = array(
			'msg' => 'логин/пароль не найдены',
			'state' => 0,
			'orderels' => $orderels,
			'address' => $address,
			'cuscont' => $cuscont,
			'status' => 0
		);
	} else {
		$q = 
		"
		SELECT 
			`name`,
			`mail`,
			`phone`,
			`mail`,
			`city`,
			`street`,
			`house`,
			`str`,
			`pod`,
			`code`,
			`etazh`,
			`flat`,
			`status`
		FROM 
			`ordernumdate`
		WHERE 
			`orderId` = '".mysqli_real_escape_string($shop, $reqOrder['orderid'])."' AND 
			`customerId` = '".$userid."'
		";
		$r = mysqli_query($shop, $q);
		if(mysqli_num_rows($r)==0){
			mysqli_free_result($r);
			$arr = array(
				'msg' => 'заказ не найден',
				'state' => 0,
				'orderels' => $orderels,
				'address' => $address,
				'cuscont' => $cuscont,
				'status' => 0
			);
		} else {
			$row = mysqli_fetch_assoc($r);
			mysqli_free_result($r);
			$address['city'] = $row['city'];
			$address['street'] = $row['street'];
			$address['address'] = $row['korp'];
			$address['korp'] = $row['str'];
			$address['etazh'] = $row['etazh'];
			$address['flat'] = $row['flat'];
			$address['domo'] = $row['code'];
			$address['house'] = $row['house'];
			$address['cuscont'] = $row['mail'];
			$cuscont['name'] = $row['name'];
			$cuscont['mail'] = $row['mail'];
			$cuscont['phone'] = $row['phone'];
			$status = $row['status'];
			$q = 
			"
			SELECT 
				`buyProdId`,
				`qtyOrder`,
				`qtyCollect`
			FROM 
				`orders`
			WHERE 
				`orderNUm` = '".mysqli_real_escape_string($shop, $reqOrder['orderid'])."'
			";
			$r = mysqli_query($shop, $q);
			while($row=mysqli_fetch_assoc($r)){
				array_push($orderels, array('id' => $row['buyProdId'], 'qty' => $row['qtyOrder'], 'qtysobr' => $row['qtyCollect']));
			}
			mysqli_free_result($r);
			$arr = array(
				'msg' => 'заказ найден',
				'state' => 1,
				'orderels' => $orderels,
				'address' => $address,
				'cuscont' => $cuscont,
				'status' => $status
			);
		}
	}
	return $arr;
}
?>