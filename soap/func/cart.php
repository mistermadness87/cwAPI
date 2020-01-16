<?php 
//работа с корзиной добавление, обновление, удаление
function cart($req) {
	global $shop;
	$udata = auth($req['auth']['login'], $req['auth']['password']);
	$t = $udata[0];
	$userid = $udata[1];
	$top = $udata[2];
	$name = $udata[3];
	$sklad = $udata[4];
	$nac = $udata[5];
	$disc = $udata[6];
	$hash = $udata[7];
	$bal = $udata[8];
	if($t==0){
		$arr = array(
			'msg' => 'логин/пароль не найдены',
			'state' => 0
		);
	} else {
		$state = 1;
		$stk = 0;
		$ostat = new ostat($shop);
		$varNm = 'rowi';
		$q = "UPDATE `cardsSubscr_mail` SET `lAct`='".date('Y-m-d H:i:s', time())."' WHERE (`id`='".$userid."')";
		mysqli_query($shop, $q);
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
		$price = new priceSetter($shop);
		$show = new show($shop);
		$cart = new cart(
			$shop,
			$price,
			$ostat,
			$show,
			$setup
		);
		
		$res = $cart->addUpdateCart(
			$userid, 
			$req['items'],
			$setup,
			$top,
			$sklad,
			$hash,
			$disc,
			$nac,
			$bal
		);
		
		if($res['balk']==2){
			$state = 0;
			$msg = "Превышен лимит, позиции не были добавлены";
		} else {
			if($res['res']['st']==1){
				$state = 2;
				$msg = 'корзина пользователя '.$name.' обновлена, но есть позиции, у которых изменено количество до доступного';
			} else {
				$state = 1;
				$msg = 'корзина пользователя '.$name.' обновлена';
			}
		}
		$arr = array(
			'msg' => $msg,
			'state' => $state,
			'sum' => $res['res']['arr']['sum'],
			'tot' => $res['res']['arr']['tot'],
			'cartitems' => $res['res']['arr']['items']
		);
	}
    return $arr;
}

function RemoveItem($itemId, $cartid, $userid){
	global $shop;
	mysqli_query($shop, "delete from cart where cookieId = '".$userid."' and itemId = '".intval($itemId)."' AND `partner` = '".mysqli_real_escape_string($shop, $cartid)."'");
}
?>