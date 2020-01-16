<?php 
//авторизация пользователя
function auth($login, $password){
	global $shop;
	$q = 
	"
	SELECT
		`cardsSubscr_mail`.`id`,
		`cardsSubscr_mail`.`name`,
		`cardsSubscr_mail`.`bal`,
		`top`,
		`sklad`,
		`pn`,
		`hash`,
		`card_types`.`discount`
	FROM
		cardsSubscr_mail
	LEFT JOIN
		`card_types` ON `card_types`.`id` = `cardsSubscr_mail`.`cardType`
	WHERE
		cardsSubscr_mail.login = '".mysqli_real_escape_string($shop, $login)."' AND 
		(
			cardsSubscr_mail.`password` = '".mysqli_real_escape_string($shop, $password)."' OR 
			cardsSubscr_mail.`password` = '".mysqli_real_escape_string($shop, md5($password))."' OR 
			MD5(`cardsSubscr_mail`.`password`) = '".mysqli_real_escape_string($shop, $_GET['password'])."'
		)
	";
	$r = mysqli_query($shop, $q);
	$t = mysqli_num_rows($r);
	$row = mysqli_fetch_assoc($r);
	$userid = intval($row['id']);
	$top = intval($row['top']);
	$name = $row['name'];
	$sklad = $row['sklad'];
	$pn = $row['pn'];
	$hash = $row['hash'];
	$bal = $row['bal'];
	$dis = floatval($row['discount']);
	mysqli_free_result($r);
	if($sklad==0){
		$sklad = 2;
	}
	$_SESSION['sklad'] = $sklad;
	$udata = array($t, $userid, $top, $name, $sklad, $pn, $dis, $hash, $bal);
	return($udata);
}
?>