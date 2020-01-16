<?php 
function avtime($req) {
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
				'state' => 0,
				'msg' => "пользователь не найден",
				'times' => array(),
			);			
		}
	}
	if($shown=1){
		$row = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		$database = "cw_".$row['id'];
		$username = "cwuser_".$row['id'];
		$shop = mysqli_connect($hostname, $username, $password, $database) or trigger_error(mysqli_error(),E_USER_ERROR);
		
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/user/user.php');
		$user = new user($shop);
		
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/tables/table.php');
		$table = new table($shop);
		
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/scheduler/schedule.php');
		$sch = new sch($shop);
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/priceClass/price.php');
		$price = new priceSetter($shop);
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/ostat/ostat.php');
		$ostat = new ostat($shop);
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/show/show.php');
		$show = new show($shop);
	
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/ord/order.php');
		$ord = new order($shop);
		$setup = $ord->setup();
		$setup = array_map('trim', $setup);
		
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/cart/cart.php');
		$cart = new cart(
			$shop,
			$price,
			$ostat,
			$show,
			$setup
		);
		if(sizeof($req['servs'])>0){
			
			//print_r($_GET);
			$html = '';
			//найдем помещения, которые оказывают подобные типы услуг
			if(!isset($req['box'])){
				$req['box'] = 0;
			}
			if($req['box']==0){
				$pom = array();
				$wr = "";
				if($req['sertype']!=0){
					$wr .= " AND `service_types_box`.`tip_uslugi` = '".mysqli_real_escape_string($shop, $req['sertype'])."'";
				}
				$q = 
				"
				SELECT
					service_types_box.id,
					CONCAT(boxes.`id`, '|', boxes.name) AS `boks`,
					CONCAT(service_types_box.tip_uslugi, '|', service_types.`name`) AS `tip_uslugi`
				FROM
					service_types_box
				INNER JOIN boxes ON boxes.id = service_types_box.boks
				INNER JOIN service_types ON service_types.id = service_types_box.tip_uslugi
				WHERE
					boxes.avtomoyka = '".mysqli_real_escape_string($shop, $req['carwash'])."' ".$wr."
				";
				$r = mysqli_query($shop, $q);
				while($row=mysqli_fetch_assoc($r)){
					array_push($pom, $row);
				}
				//print_r($pom);
				$pomm = "";
				$pomArr = array();
				for($i=0;$i<sizeof($pom);$i++){
					if($i!=0){
						$pomm .= ", ";
					}
					$boks = explode('|', $pom[$i]['boks']);
					$pomArr[$i] = $boks[0];
					$pomm .= "'".$boks[0]."'";
				}
			} else {
				$pom = $table->getData($shop, "boxes", " WHERE `boxes`.`id` = '".mysqli_real_escape_string($shop, $req['box'])."' ");
				$pomArr[0] = $pom[0]['id'];
			}
			
			//попробуем высчитать доступные часы и даты
			//сначала смотрим сколько длятся наши услуги
			$serArr = array();
			$dArr = array();

			$okTime = array();

			$l = 0;
			$con = "";
			for($i=0;$i<sizeof($req['servs']);$i++){
				if($i!=0){
					$con .= ", ";
				}
				$con .= "'".mysqli_real_escape_string($shop, $req['servs'][$i]['id'])."'";
				$qtyarr[$req['servs'][$i]['id']] = $req['servs'][$i]['qty'];
			}
			
			$q = 
			"
			SELECT
				Services.`id`,
				Services.`name`,
				service_mod_pr_time.prodolzhitelnost,
				service_mod_pr_time.cena
			FROM
				cars
			INNER JOIN service_mod_pr_time ON service_mod_pr_time.avto = cars.klas
			INNER JOIN Services ON Services.id = service_mod_pr_time.usluga
			WHERE
				cars.id = '".mysqli_real_escape_string($shop, $req['model'])."' AND 
				service_mod_pr_time.usluga IN (".$con.")
			";
			//echo $q.'<br />';
			$serarr = array();
			$r = mysqli_query($shop, $q);
			while($row=mysqli_fetch_assoc($r)){
				array_push($serarr, 
					array(
						'id' => $row['id'],
						'name' => $row['name'],
						'price' => 0,
						'last' => $row['prodolzhitelnost'],
					)
				);			
			}
			mysqli_free_result($r);
			//
			$sum = 0;
			for($i=0;$i<sizeof($serarr);$i++){
				$l += $serarr[$i]['last'] * $qtyarr[$serarr[$i]['id']];
			}
			$dl = 1;
			//print_r($sch);
			/* вот здесь мы закончили с записью */
			$ct = strtotime($req['date']);
			
			$m = $table->getData($shop, 'carwashes', " WHERE `carwashes`.`id` = '".mysqli_real_escape_string($shop, $req['carwash'])."' ");
			$st = $m[0]['otkryvaetsya'];
			$end = $m[0]['zakryvaetsya'];
			$cc = strtotime(date('d.m.Y', $ct).' '.date('H:i', strtotime($end)));
			//echo $cc;
			$range = range(strtotime(date('d.m.Y', $ct).' '.$st), $cc, 10 * 60);
			//print_r($range);
			//echo $dt;
			$order = 0;
			$tk = 1;

			$sotr = 0;
			$okTime = $sch->checkFor($order, $st, $end, $ct, $tk, $cc, $pomArr, $table, $dl, $range, $l, $sotr)['time'];
			
			//теперь пройдем по массиву и найдем даты, которые заняты
			$disd = array();
			foreach($dArr AS $k => $v){
				if($v==0){
					array_push($disd, $k);
				}
			}
			ksort($okTime);

			$keyend = key($okTime);

			reset($okTime);
			$keyst = key($okTime);

			$range = range(strtotime($keyst), strtotime($keyend), 30 * 60);
			$skipArr = array('10', '20', '40', '50');
			$timearr = array();
			foreach($okTime AS $k => $v){
				array_push($timearr, array('val' => $k));
			}
			if(sizeof($okTime)==0){
				$arr = array(
					'state' => 0,
					'msg' => "Свободного времени на ".$req['date']." нет",
					'times' => 'говно',
				);				
			} else {
				$arr = array(
					'state' => 1,
					'msg' => "Свободное временя на ".$req['date']." есть",
					'times' => $timearr,
				);					
			}
		} else {
			$arr = array(
				'state' => 0,
				'msg' => "Вы не ввели ни одной услуги",
				'times' => array(),
			);						
		}
				
	}
	return $arr;
}
?>