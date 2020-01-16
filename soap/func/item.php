<?php 
//получение остатка товара онлайн
function item($req) {
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
			'state' => 0,
			'stock' => 0,
			'price' => 0,
		);
	} else {
		if($sklad==0){
			$qS = "SELECT `id` FROM `sklad` WHERE `osn` = '1'";
			$rS = mysqli_query( $shop, $qS);
			$rowS = mysqli_fetch_assoc($rS);
			$sklad = $rowS['id'];
			mysqli_free_result($rS);
		}
		$qs = "SELECT * FROM `setup` ORDER BY `id` ASC";
		$rs = mysqli_query($shop, $qs);
		$setup = array();
		while($rows=mysqli_fetch_assoc($rs)){
			array_push($setup, $rows['value']);
		}
		mysqli_free_result($rs);
		$show = new show($shop);
		$wrShow = $show->show($sklad, $userid, 0, $top);
		$wrJoin = $show->joinTbl($top);
		$wrF = $show->fTbl($top);
		$qi = 
		"
		SELECT 
			".$wrF."
		FROM 
			`items`
			".$wrJoin."
		WHERE 
			`itemId` = '".mysqli_real_escape_string($shop, $req['itemid'])."' AND
			".$wrShow."
		";
		error_log($qi, 0);
		$ri = mysqli_query($shop, $qi);
		$rowi = mysqli_fetch_assoc($ri);
		$tt = mysqli_num_rows($ri);
		mysqli_free_result($ri);
		
		//$tt = 0;
		if($tt==0){
			$arr = array(
				'msg' => 'позиция '.$req['itemid'].' не найдена',
				'state' => 0,
				'stock' => 0,
				'price' => 0,
			);	
		} else {
			$varNm = 'rowi';

			//получаем текущую цену
			$price = new priceSetter($shop);
			$preord = ${$varNm}['preord'];
			
			if(!isset(${$varNm}['optdis'])){
				${$varNm}['optdis'] = 0;
			}
			if(!isset(${$varNm}['dopdis'])){
				${$varNm}['dopdis'] = 0;
			}
			if(!isset(${$varNm}['dopdisdate'])){
				${$varNm}['dopdisdate'] = 0;
			}
			
			$prarr = array(
				'priceBase' => ${$varNm}['priceBase'],
				'preuro' => ${$varNm}['preuro'],
				'itemPriceNew' => ${$varNm}['itemPriceNew'],
				'prhold' => ${$varNm}['prhold'],
				'crr' => ${$varNm}['upd'],
				'ddis' => ${$varNm}['optdis'],
				'dopdis' => ${$varNm}['dopdis'],
				'dopdisdate' => ${$varNm}['dopdisdate'],
			);
			switch(${$varNm}['upd']){
				case 1:
					$rate = $_SESSION['rate_us'];
					break;
				case 2:
					$rate = $_SESSION['rate'];
					break;
				case 3:
					$rate = 1;
					break;
			}
			$qty = 1;
			$prres = $price->setPrice(
				$top,
				$nac, 
				$disc, 
				$prarr,
				$qty,
				0,
				$preord,
				$rate,
				$userid,
				${$varNm}['itemId'],
				$setup,
				0
			);
			
			//получаем доступный остаток
			$ostat = new ostat($shop);
			$dst = $ostat->getDost(${$varNm}['itemId'], $sklad, ${$varNm}['order'], ${$varNm}['skip'], $top, $setup[33]);
			$prrr = 0;
			if($top==2){
				$prrr = $prres['prnewfrom'];
			}
			$arr = array(
				'msg' => 'остаток позиции '.$req['itemid'],
				'state' => 1,
				'stock' => $ostat->dostConv($dst['dost']),
				'price' => $prres[$prres['pruse']],
				'price2' => $prrr,
			);	
		}
	}
    return $arr;
}
?>