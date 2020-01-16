<?php 
//выборка из таблицы
function clients($req) {
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
			'clients' => array(),
		);
	} else {
		$q = 
		"
		SELECT
			cardsSubscr_mail.id,
			cardsSubscr_mail.`name`,
			cardsSubscr_mail.`hash`
		FROM
			carWashSells
		INNER JOIN cardsSubscr_mail ON cardsSubscr_mail.id = carWashSells.kontragent
		GROUP BY
			cardsSubscr_mail.id
		ORDER BY
			cardsSubscr_mail.id ASC
		";
		$r = mysqli_query($shop, $q);
		$res = array();
		$i = 0;
		while($row=mysqli_fetch_assoc($r)){
			$res[$i] = $row;
			$i++;
		}
		mysqli_free_result($r);
		$arr = array(
			'msg' => 'клиенты кох-центры',
			'state' => 0,
			'clients' => $res,
		);
	}
	
	return $arr;
}
?>