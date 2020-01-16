<?php 
//отправка заказа
function sendCart($orderData){
	global $shop;
	$udata = auth($orderData['auth']['login'], $orderData['auth']['password']);
	$t = $udata[0];
	$userid = $udata[1];
	$topp = $udata[2];
	$name = $udata[3];
	$sklad = $udata[4];
	$nac = $udata[5];
	$disc = $udata[6];
	$hash = $udata[7];
	$bal = $udata[8];
	$uOptSt = $topp;
	$cartitems = array();
	if($t==0){
		$arr = array(
			'msg' => 'логин/пароль не найдены',
			'state' => 0,
			'ordernum' => 0
		);
	} else {
		
		$val = validateNumber($orderData['cuscont']['phone']);
		if($val==0){
			$arr = array(
				'msg' => 'неверный формат телефона',
				'state' => 1,
				'ordernum' => '0'
			);
		} else {
			$ostat = new ostat($shop);
			$price = new priceSetter($shop);
			$show = new show($shop);
			$qs = "SELECT * FROM `setup` ORDER BY `id` ASC";
			$rs = mysqli_query($shop, $qs);
			$setup = array();
			while($rows=mysqli_fetch_assoc($rs)){
				array_push($setup, trim($rows['value']));
			}
			mysqli_free_result($rs);
			$cart = new cart(
				$shop,
				$price,
				$ostat,
				$show,
				$setup
			);
			
			$wrShow = $show->show($sklad, $userid);
			
			$req = " AND `cart`.`partner` IN ('".$orderData['cartid']."')";
			
			$ord = 1;
			$clk = 0;
			$arr = $cart->getData(
				$req, 
				$hash, 
				$topp, 
				$disc, 
				$nac, 
				$userid, 
				$setup,
				$sklad,
				$wrShow,
				$clk,
				$ord
			);
			
			if($arr['arr']['tot']>0){
				if($arr['arr']['ord']!=0){
					$opar = array_merge($orderData['cuscont'], $orderData['address'], $orderData['ordPar']);
					$res = $cart->sendCart(
						$hash,
						$userid,
						$setup,
						$opar,
						$uOptSt,
						4
					);
					if($res['st']==1){
						//все прошло хорошо. Заказ добавлен
						$q = "UPDATE `orders` SET `orderNum` = '".$res['ord']."' WHERE `cid` = '".$hash."'";
						mysqli_query($shop, $q);
						$q = "UPDATE `orders` SET `cid` = NULL WHERE `orderNum` = '".$res['ord']."'";
						mysqli_query($shop, $q);
						$q = "UPDATE `book` SET `order` = '".$res['ord']."' WHERE `cid` = '".$hash."'";
						mysqli_query($shop, $q);
						$q = "UPDATE `book` SET `cid` = NULL WHERE `order` = '".$res['ord']."'";
						mysqli_query($shop, $q);
						$q = 
						"
						DELETE FROM 
							`cart` 
						WHERE 
							`cart`.`cookieId` = '".$hash."'
							".$req."
							";
						//echo $q;
						mysqli_query($shop, $q);
						$date = date("Y-m-d H:i:s", time());
						
						$q = "SELECT * FROM `pay_type` WHERE `id` = '".$opar['payType']."'";
						//echo $q;
						$r = mysqli_query( $shop, $q);
						$row = mysqli_fetch_assoc($r);
						$beznal = $row['beznal'];
						$ptnm = $row['name'];
						mysqli_free_result($r);
						
						$q = "SELECT `name` FROM `del_type` WHERE `id` = '".$opar['delType']."'";
						$r = mysqli_query( $shop, $q);
						$row = mysqli_fetch_assoc($r);
						$dtnm = $row['name'];
						mysqli_free_result($r);
						
						if(!isset($orderData['prPred'])){
							$orderData['prPred'] = 0;
						}
						if(!isset($orderData['ordType'])){
							$orderData['ordType'] = 0;
						}
						//error_log("запрос на выходе ".print_r($orderData, true), 0);
						//error_log("цена на товар ".$_SERVER['REMOTE_ADDR'], 0);
						//есть предложение цены
						$msgAPI = "Поступил новый<br />заказ через API!";
						if($_SERVER['REMOTE_ADDR']=='83.166.240.32') {
							if(intval($orderData['ordType'])==1){
								$q = "UPDATE `ordernumdate` SET `comm` = '<p><strong>Заказ по предложению цены</strong></p>' WHERE `orderId` = '".$res['ord']."'";
								mysqli_query($shop, $q);
								$msgAPI = "Поступил новый заказ<br />предложение цены<br />заказ через API!";
								$q = "UPDATE `orders` SET `newprice` = '".$orderData['prPred']."', `topbay` = '2', `prStart` = '1' WHERE `orderNum` = '".$res['ord']."'";
								mysqli_query($shop, $q);
								$arr['arr']['items'][0]['price'] = $orderData['prPred'];
								$arr['arr']['sum'] = $orderData['prPred'];
							} else if($orderData['ordType']==2){
								$q = "UPDATE `ordernumdate` SET `comm` = '<p><strong>Заказ сервиса хочу в подарок</strong></p>', `adress` = '1', `payTypeWay` = '1' WHERE `orderId` = '".$res['ord']."'";
								mysqli_query($shop, $q);
								$msgAPI = "Поступил новый заказ<br />хочу в подарок<br />заказ через API!";
								$q = "UPDATE `orders` SET `topbay` = '1', `prStart` = '1' WHERE `orderNum` = '".$res['ord']."'";
								mysqli_query($shop, $q);
								$arr['arr']['items'][0]['price'] = $orderData['prPred'];
								$arr['arr']['sum'] = $orderData['prPred'];
							}
						}
						
						$delCost = 0;
						
						$mBody = $cart->genMail($res['ord'], $date, $arr['arr']['items'], $setup, $ptnm, $dtnm, $delCost, $arr['arr']['sum'], $opar['mail'], $opar['phone'], $opar['name']);

						$subject = 'Заказ # '.$res['ord'].' на сайте '.$setup[8];
						$to = $opar['mail'];
						//echo $to;
						include($_SERVER['DOCUMENT_ROOT'].'/sendMail.php');
						$to = $setup[38];
						//echo $to;
						include($_SERVER['DOCUMENT_ROOT'].'/sendMail.php');
						$q = "SELECT `id` FROM `manag` WHERE `comet` = '1'";
						$r = mysqli_query( $shop, $q);
						while($row=mysqli_fetch_assoc($r)){
							//echo '/usr/bin/curl '.$setup[32].'://'.$setup[8].'/pub/?id=order_'.$row['id'].' --insecure -d "Поступил новый заказ!<br /><a href="#" class=\'zayav2\' rel="'.$last.'">к заказам...</a>"';
							shell_exec('/usr/bin/curl '.$setup[32].'://'.$setup[8].'/pub/?id=order_'.$row['id'].' --insecure -d "'.$msgAPI.'<br /><a href="#" class=\'zayav2\' rel="'.$res['ord'].'">к заказам...</a>"');
						}
						$arr = array(
							'msg' => 'заказ успешно сформирован',
							'state' => 1,
							'ordernum' => $res['ord']
						);
					} else {
						//не хватает информации по заказу или количество изменилось
						$q = 
						"
						DELETE FROM 
							`book` 
						WHERE 
							`book`.`cid` = '".$hash."'";
						mysqli_query($shop, $q);
						$q = 
						"
						DELETE FROM 
							`orders` 
						WHERE 
							`orders`.`cid` = '".$hash."'";
						mysqli_query($shop, $q);
						$arr = array(
							'msg' => 'неверная информация о заказе',
							'state' => 0,
							'ordernum' => 0
						);
					}
				} else {
					$arr = array(
						'msg' => 'остатки товара изменились',
						'state' => 0,
						'ordernum' => 0
					);
				}
			} else {
				$arr = array(
					'msg' => 'корзина пуста'.print_r($arr, true),
					'state' => 0,
					'ordernum' => 0
				);
			}
		}
	}
	return $arr;
}

function validateNumber($value) {
    $formats = array(
        '###-###-####', '####-###-###',
        '#(###) ###-####','####-####-####',
        '##-###-####-####','####-####','###-###-###',
        '#####-###-###', '##########', '#########',
        '# ### #####', '#-### #####', '+#(###) ###-####'
    );
	$format = trim(preg_replace('/[0-9]/', '#', $value));
    return (in_array($format, $formats)) ? 1 : 0;
}
?>