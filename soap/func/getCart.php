<?php 
//получение содержимого корзины
function getCart($reqCart){
	global $shop;
	$udata = auth($reqCart['auth']['login'], $reqCart['auth']['password']);
	$t = $udata[0];
	$userid = $udata[1];
	$top = $udata[2];
	$name = $udata[3];
	$sklad = $udata[4];
	$nac = $udata[5];
	$disc = $udata[6];
	$hash = $udata[7];
	$bal = $udata[8];
	$cartitems = array();
	$state = 0;
	if($t==0){
		$arr = array(
			'msg' => 'логин/пароль не найдены',
			'state' => 0,
			'sum' => 0,
			'cartitems' => $cartitems,
		);
	} else {
		$ostat = new ostat($shop);
		$price = new priceSetter($shop);
		$show = new show($shop);
		
		$cart = new cart(
			$shop,
			$price,
			$ostat,
			$show,
			$setup
		);

		$qs = "SELECT * FROM `setup` ORDER BY `id` ASC";
		$rs = mysqli_query($shop, $qs);
		$setup = array();
		while($rows=mysqli_fetch_assoc($rs)){
			array_push($setup, $rows['value']);
		}
		mysqli_free_result($rs);
		
		if($sklad==0){
			$qS = "SELECT `id` FROM `sklad` WHERE `osn` = '1'";
			$rS = mysqli_query( $shop, $qS);
			$rowS = mysqli_fetch_assoc($rS);
			$sklad = $rowS['id'];
			mysqli_free_result($rS);
		}
		$req = "";
		if(sizeof($reqCart['cartids'])>0){
			$req .= " AND `cart`.`partner` IN (";
			for($i=0;$i<sizeof($reqCart['cartids']);$i++){
				if($i!=0){
					$req .= ", ";
				}
				$req .= "'".mysqli_real_escape_string($shop, $reqCart['cartids'][$i]['id'])."'";
			}
			$req .= ")";
		}
		
		$wrShow = $show->show($sklad, $userid);
		
		$cart = new cart(
			$shop,
			$price,
			$ostat,
			$show,
			$setup
		);
		
		$arr = $cart->getData(
			$req, 
			$hash, 
			$top, 
			$disc, 
			$nac, 
			$userid, 
			$setup,
			$sklad,
			$wrShow
		);
		
		if(sizeof($arr['arr']['items'])==0){
			$msg = 'корзина пуста';
			$state = 0;	
		} else {
			if($arr['st']==1){
				$msg = 'содержимое корзины было изменено';
				$state = 2;
			} else {
				$msg = 'содержимое корзины';
				$state = 1;	
			}
		}
		
		$arr = array(
			'msg' => $msg,
			'state' => $state,
			'sum' => $arr['arr']['sum'],
			'cartitems' => $arr['arr']['items'],
		);
	}
	return $arr;
}
?>