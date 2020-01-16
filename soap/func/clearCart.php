<?php 
//очистка корзины
function clearCart($reqCart){
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
	if($t==0){
		$arr = array(
			'msg' => 'логин/пароль не найдены',
			'state' => 0
		);
	} else {
		$req = "(";
		for($i=0;$i<sizeof($reqCart['cartids']);$i++){
			if($i!=0){
				$req .= ", ";
			}
			$req .= "'".mysqli_real_escape_string($shop, $reqCart['cartids'][$i]['id'])."'";
		}
		$req .= ")";

		$q = 
		"
		DELETE FROM 
			`cart`
		WHERE 
			(
				`cookieId` = '".$hash."' AND 
				`cart`.`partner` IN ".$req."
			)
		";
		mysqli_query($shop, $q);
		$arr = array(
			'msg' => 'корзина очищена',
			'state' => 1
		);
	}
	return $arr;
}
?>