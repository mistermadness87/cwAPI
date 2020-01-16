<?php 
//выборка из таблицы
function recData($req) {
	global $shop;
	global $password;
	global $hostname;
	$q = "SELECT `id` FROM `cardsSubscr_mail` WHERE `hash` = '".mysqli_real_escape_string($shop, $req['auth'])."abebe'";
	$r = mysqli_query($shop, $q);
	$shown = 1;
	if(mysqli_num_rows($r)==0){
		mysqli_free_result($r);
		$q = "SELECT `id` FROM `cardsSubscr_mail` WHERE `hash` = '".mysqli_real_escape_string($shop, $req['auth'])."'";
		$r = mysqli_query($shop, $q);
		if(mysqli_num_rows($r)==0){
			$shown = 0;
			$arr = array(
				'msg' => 'пользователь не найден',
				'state' => 0,
				'priceels' => array(),
			);			
		}
	}
	if($shown==1){
		$row = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		$database = "cw_".$row['id'];
		$username = "cwuser_".$row['id'];
		$shop = mysqli_connect($hostname, $username, $password, $database) or trigger_error(mysqli_error(),E_USER_ERROR);
		$table = new table($shop);
		$wr = 
		" 
		WHERE 
			`book_orders`.`id` = '".mysqli_real_escape_string($shop, $req['id'])."'
		";
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/scheduler/schedule.php');
		$sch = new sch($shop);
		$q = 
		"
		SELECT
			book_orders.`status`,
			boxes.otkrytie,
			boxes.zakrytie,
			boxes.`id` AS `bId`,
			boxes.`name` AS `bName`,
			carwashes.id AS `cwId`,
			carwashes.`name`,
			carwashes.adres,
			book_orders.`date`,
			book_orders.data_cheka,
			book_orders.`chek`,
			book_orders.`skidka`,
			book_orders.komentariy,
			book_orders.akciya,
			book_orders.nomer_proc,
			book_orders.status_proc,
			Akcii_carwash.name AS `aName`,
			cardsSubscr_mail.`name` AS `cName`,
			cardsSubscr_mail.`id` AS `cId`,
			cardsSubscr_mail.`phone`,
			cardsSubscr_mail.`mail`,
			cars.klas,
			cars.model,
			cars.id AS `carId`,
			car_marka.`name` AS `mName`,
			ord_states.zakryt,
			ord_states.cvet,
			ord_states.pechat_zakaznaryada,
			ord_states.izmenyat,
			ord_states.konechny,
			ord_states.`name` AS `stName`,			
			manag.id AS `mId`,
			manag.name AS `mNm`,
			pay_type.`id` AS `ptId`,
			pay_type.`mod`,
			pay_type.`name` AS `ptName`,
			car_class.`name` AS `class`,
			book_orders.gos_nomer
		FROM
			book_orders
		INNER JOIN boxes ON boxes.id = book_orders.boks
		INNER JOIN carwashes ON carwashes.id = boxes.avtomoyka
		INNER JOIN cardsSubscr_mail ON cardsSubscr_mail.id = book_orders.kontragent
		INNER JOIN cars ON cars.id = book_orders.avto
		INNER JOIN car_marka ON car_marka.id = cars.marka
		INNER JOIN car_class ON car_class.id = cars.klas
		INNER JOIN ord_states ON ord_states.id = book_orders.`status`
		LEFT JOIN manag ON manag.id = book_orders.`sotrudnik`
		LEFT JOIN Akcii_carwash ON Akcii_carwash.id = book_orders.`akciya`
		LEFT JOIN pay_type ON pay_type.id = book_orders.`sposob_oplaty`
		".$wr;
		$res = array();
		$r = mysqli_query($shop, $q);
		$row = mysqli_fetch_assoc($r);
		$res = $sch->odata($req['id']);
		mysqli_free_result($r);
		$r = array();
		for($i=0;$i<sizeof($res['data']);$i++){
			$r[$i] = array(
				'id' => $res['data'][$i]['serid'],
				'code' => $res['data'][$i]['kod'],
				'name' => $res['data'][$i]['ser'],
				'price' => $res['data'][$i]['p'],
				'q' => $res['data'][$i]['q'],
				'last' => $res['data'][$i]['lone']
			);
		}
		
		$arr = array(
			'state' => 1,
			'msg' => 'информация о записи ',
			'date' => date('d.m.Y', strtotime($row['date'])),
			'carwash' => $row['name'],
			'post' => $row['bName'],
			'client' => $row['cName'],
			'mail' => $row['mail'],
			'phone' => $row['phone'],
			'payt' => $row['ptName'],
			'disc' => $row['skidka'],
			'time' => date('H:i', strtotime($row['date'])),
			'comment' => $row['komentariy'],
			'admin' => $row['mNm'],
			'car' => $row['mName'].' '.$row['model'].', класс '.$row['class'],
			'status' => $row['stName'],
			'recSerLists' => $r,
		);				
	}
	
	return $arr;
}
?>