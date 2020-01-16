<?php 
function longrec($req) {
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
				'id' => 0,
				'clid' => 0,
			);			
		}
	}
	if($shown=1){
		
		$vph = validateNumberMyOwn($req['phone']);
		if($vph){
			$vph = validateEmailMyOwn($req['mail']);
			if($vph){
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
				
				$id = $req['clid'];
				if($id==0){
					//найдем пользователя по телефону
					$id = 0;
					$q = 
					"
					SELECT 
						`id`
					FROM 
						`cardsSubscr_mail`
					WHERE 
						`phone` = '".mysqli_real_escape_string($shop, $req['phone'])."'
					";
					$r = mysqli_query($shop, $q);
					if(mysqli_num_rows($r)>0){
						$row = mysqli_fetch_assoc($r);
						$id = $row['id'];
					}
					mysqli_free_result($r);
				}
				if($id==0){
					//найдем пользователя по почте
					$id = 0;
					$q = 
					"
					SELECT 
						`id`
					FROM 
						`cardsSubscr_mail`
					WHERE 
						`mail` = '".mysqli_real_escape_string($shop, $req['mail'])."'
					";
					$r = mysqli_query($shop, $q);
					if(mysqli_num_rows($r)>0){
						$row = mysqli_fetch_assoc($r);
						$id = $row['id'];
					}
					mysqli_free_result($r);
				}				
				if($id==0){
					//мы не найшли пользователя, зарегим
					$s = 0;
					if(isset($req['subscr'])){
						if($req['subscr']==1){
							$s = 1;
						}
					}
					$ur = $user->register($setup, $req['phone'], $req['mail'], $req['name'], '', $s, 1);
					$id = $ur['id'];
					$q = "INSERT INTO `user_cars` (`kontragent`, `mashina`, `gos_nomer`) VALUES ('".$id."', '".mysqli_real_escape_string($shop, $req['model'])."', '".mysqli_real_escape_string($shop, $req['gosnomer'])."')";
					mysqli_query($shop, $q);
					$lc = mysqli_insert_id($shop);
				} else {
					//если мы нашли пользователя, то поищем у него машину
					$q = "SELECT `id` FROM `user_cars` WHERE `kontragent` = '".$id."' AND `mashina` = '".mysqli_real_escape_string($shop, $req['model'])."'";
					$r = mysqli_query($shop, $q);
					if(mysqli_num_rows($r)==0){
						$row = mysqli_fetch_assoc($r);
						$q = "INSERT INTO `user_cars` (`kontragent`, `mashina`, `gos_nomer`) VALUES ('".$id."', '".mysqli_real_escape_string($shop, $req['model'])."', '".mysqli_real_escape_string($shop, $req['gosnomer'])."')";
						mysqli_query($shop, $q);
						$lc = mysqli_insert_id($shop);
					} else {
						$lc = $row['id'];
					}
					mysqli_free_result($r);
				}
				if(sizeof($req['servs'])>0){
					//начнем запись
					$time = $req['time'];
					$model = $table->getData($shop, "cars", " WHERE `cars`.`id` = '".mysqli_real_escape_string($shop, $req['model'])."' ");
					$marka = $table->getData($shop, "car_marka", " WHERE `car_marka`.`id` = '".mysqli_real_escape_string($shop, $req['marka'])."' ");
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
					
					$paytp = $table->getData($shop, "pay_type", " WHERE `pay_type`.`beznal` = '0' ");
					$disc = 0;
					$mod = 0;
					
					//попробуем высчитать доступные часы и даты
					//сначала смотрим сколько длятся наши услуги
					$serArr = array();
					$dArr = array();
					$okTime = array();
					$l = 0;
					$dis = mysqli_real_escape_string($shop, $req['disc']);
					
					if($id!=0){
						$us = $table->getData($shop, "cardsSubscr_mail", " WHERE `cardsSubscr_mail`.`id` = '".mysqli_real_escape_string($shop, $id)."' ");
						$ud = explode('|', $us[0]['skidka_na_moyku']);
						$ud = $ud[0];
					}

					$d = $table->getData($shop, "car_wash_disc", " WHERE `car_wash_disc`.`id` = '".mysqli_real_escape_string($shop, $ud)."' ");
					if(sizeof($d)>0){
						$d = $d[0]['znachenie'];
					} else {
						$d = 0;
					}
					
					$dis += $d;
					$qtyarr = array();
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
						$pr = number_format(round(($row['cena']*((100-$dis)/100)), 2), 2, '.', '');
						array_push($serarr, 
							array(
								'id' => $row['id'],
								'name' => $row['name'],
								'price' => $pr,
								'last' => $row['prodolzhitelnost'],
							)
						);			
					}
					mysqli_free_result($r);
					//
					$akc = 0;
					$arr = array();
					$arr['arr']['items'] = array();
					$sum = 0;
					for($i=0;$i<sizeof($serarr);$i++){
						$arr['arr']['items'][$i]['itemName'] = $serarr[$i]['name'];
						$arr['arr']['items'][$i]['price'] = $serarr[$i]['price'];
						$arr['arr']['items'][$i]['last'] = $serarr[$i]['last'];
						$arr['arr']['items'][$i]['qty'] = $qtyarr[$serarr[$i]['id']];
						$l += $serarr[$i]['last'] * $qtyarr[$serarr[$i]['id']];
						$akc = 0;
						$sum += $serarr[$i]['price'] * $qtyarr[$serarr[$i]['id']];
					}
					$arr['arr']['sum'] = $sum;
					$dl = 1;
					//print_r($sch);
					/* вот здесь мы закончили с записью */
					$ct = strtotime($req['date']);
					
					$m = $table->getData($shop, 'carwashes', " WHERE `carwashes`.`id` = '".mysqli_real_escape_string($shop, $req['carwash'])."' ");
					$st = $m[0]['otkryvaetsya'];
					$end = $m[0]['zakryvaetsya'];

					$cc = strtotime(date('d.m.Y', $ct).' 23:50');
					//echo $cc;
					$endOrd = strtotime($req['date'].' '.$time) + $l * 60;
					//echo $endOrd;
					//echo $cc;
					$range = strtotime($time);
					//print_r($range);
					//echo $dt;
					$order = 0;
					$tk = 1;

					$sotr = 0;

					$okTime = $sch->checkFor($order, $st, $end, $ct, $tk, $cc, $pomArr, $table, $dl, $range, $l, $sotr, 1);
					$res = array();
					//print_r($okTime);
					//exit;
					if($okTime['gettime']==1){
						//это время свободно, делаем запись
						
						$q = 
						"
						INSERT INTO 
							`book_orders` (
								`date`, 
								`data_konec`,
								`kontragent`, 
								`boks`, 
								`avto`, 
								`gos_nomer`, 
								`book_orders_serv`, 
								`status`,
								`sotrudnik`,
								`akciya`,
								`sposob_oplaty`,
								`skidka`
							) VALUES (
								'".date('Y-m-d H:i', strtotime(date('d.m.Y', $ct).' '.$time))."', 
								'".date('Y-m-d H:i', $endOrd)."', 
								'".$id."', 
								'".$okTime['box'][0]."', 
								'".mysqli_real_escape_string($shop, $req['model'])."', 
								'".mysqli_real_escape_string($shop, $req['gosnomer'])."', 
								'0',
								'1',
								'1',
								'".$akc."',
								'".$paytp[0]['id']."',
								'".mysqli_real_escape_string($shop, $req['disc'])."'
							)
						";
						//echo $q;
						mysqli_query($shop, $q);
						$last = mysqli_insert_id($shop);
						
						$hash = md5($last.rand());
						$q = "UPDATE `book_orders` SET `hesh` = '".$hash."' WHERE `id` = '".$last."'";
						mysqli_query($shop, $q);
						
						//print_r($serArr);
						
						for($j=0;$j<sizeof($serarr);$j++){
							$q = 
							"
							INSERT INTO 
								`book_orders_serv` (
									`zakaz`, 
									`usluga`, 
									`cena`, 
									`prodolzhitelnost`,
									`sozdatel`,
									`akciya`,
									`qty`
								) VALUES (
									'".$last."', 
									'".$serarr[$j]['id']."', 
									'".$serarr[$j]['price']."', 
									'".$serarr[$j]['last']."',
									'0',
									'0',
									'".$qtyarr[$serarr[$j]['id']]."'
								) ON DUPLICATE KEY UPDATE 
									`prodolzhitelnost`=VALUES(`prodolzhitelnost`),
									`cena`=VALUES(`cena`),
									`akciya`=VALUES(`akciya`)
							";
							//echo $q;
							mysqli_query($shop, $q);
						}
						
						$ptnm = '';
						$dtnm = '';
						$delCost = 0;
						
						$q = 
						"
						SELECT
							carwashes.adres
						FROM
							carwashes
						INNER JOIN boxes ON boxes.avtomoyka = carwashes.id
						WHERE
							boxes.id = '".$okTime['box'][0]."'
						";
						$r = mysqli_query($shop, $q);
						$row = mysqli_fetch_assoc($r);
						mysqli_free_result($r);
						
						$addtxt = 
						'
						<div class="bookConfirm">
							'.$marka[0]['name'].' '.$model[0]['model'].' - '.explode('|', $model[0]['klas'])[1].' класс
						</div>
						<div class="bookConfirm">
							'.date('d.m.Y', strtotime($req['date'])).' на '.$req['time'].'
						</div>
						<div class="bookConfirm">
							способ оплаты '.$paytp[0]['name'].'
						</div>
						<div class="bookConfirm">
							адрес '.strip_tags($row['adres']).'
						</div>
						<div class="bookConfirm">
							бокс
							';	
						if($req['box']==0){
							$addtxt .= 'не важно';
						} else {
							$box = $table->getData($shop, "boxes", " WHERE `boxes`.`id` = '".mysqli_real_escape_string($shop, $req['box'])."' ");
							$addtxt .= $box[0]['name'];
						}
						$addtxt .=
						'
						</div>
						<div class="bookConfirm">
							сотрудник 
						';
						$addtxt .= 'не важно';
						$addtxt .=
						'
						</div>
						';
						$date = date('Y-m-d H:i:s', time());
						$mail = $req['mail'];
						$phone = $req['phone'];
						$nameu = $req['name'];
						$mBody = $cart->genMail($last, $date, $arr['arr']['items'], $setup, $ptnm, $dtnm, $delCost, $arr['arr']['sum'], $mail, $phone, $nameu, '0', 1, $addtxt);
						$fromMail = $setup[48];
						$subject = 'Заказ автомойки # '.$last.' на сайте '.$setup[8];
						$to = $mail;
						//echo $to;
						include($_SERVER['DOCUMENT_ROOT'].'/sendMail.php');
						$to = $setup[38];
						//echo $to;
						include($_SERVER['DOCUMENT_ROOT'].'/sendMail.php');
						$q = "SELECT `id` FROM `manag` WHERE `comet` = '1'";
						$r = mysqli_query( $shop, $q);
						while($row=mysqli_fetch_assoc($r)){
							//echo '/usr/bin/curl '.$setup[32].'://'.$setup[8].'/pub/?id=order_'.$row['id'].' --insecure -d "Поступил новый заказ!<br /><a href="#" class=\'zayav2\' rel="'.$res['ord'].'">к заказам...</a>"';
							shell_exec('/usr/bin/curl '.$setup[32].'://'.$setup[8].'/pub/?id=order_'.$row['id'].' --insecure -d "Поступил новый заказ автомойки!<br /><a href="#" class=\'zayavavtom\' rel="'.$last.'">к заказам...</a>"');
						}
						mysqli_free_result($r);
						$arr = array(
							'state' => 1,
							'msg' => "Запись успешно создана, номер ".$last,
							'id' => $last,
							'clid' => $id,
						);	
					} else {
						$arr = array(
							'state' => 0,
							'msg' => "Время и дата недоступны для записи",
							'id' => 0,
							'clid' => 0,
						);						
					}
					
				} else {
					$arr = array(
						'state' => 0,
						'msg' => "Вы не ввели ни одной услуги",
						'id' => 0,
						'clid' => 0,
					);						
				}
				
				/*
				mysqli_free_result($r);
				$q = 
				"
				INSERT INTO 
					`book_order_call`
				(
					`imya`,
					`phone`,
					`marka_avto`,
					`model_avto`,
					`avtomoyka`,
					`zhelaemaya_data`,
					`data`
				)
				VALUES (
					'".$id."',
					'".mysqli_real_escape_string($shop, $req['phone'])."',
					'".mysqli_real_escape_string($shop, $req['marka'])."',
					'".mysqli_real_escape_string($shop, $req['model'])."',
					'".mysqli_real_escape_string($shop, $req['carwash'])."',
					'".date("Y-m-d", strtotime($req['date']))."',
					'".date("Y-m-d", time())."'
				)
				";
				mysqli_query($shop, $q);
				$l = mysqli_insert_id($shop);
				$arr = array(
					'state' => 1,
					'msg' => "заявка на запись успешно создана",
					'id' => $l,
					'clid' => $id,
				);
				
				$q = "SELECT `id` FROM `manag` WHERE `comet` = '1'";
				$r = mysqli_query( $shop, $q);
				while($row=mysqli_fetch_assoc($r)){
					$msgAPI = 'новая запись';
					$msg = '/usr/bin/curl '.$setup[32].'://'.$setup[8].'/pub/?id=order_'.$row['id'].' --insecure -d "'.$msgAPI.'<br /><a href="#" class=\'zayavreq\' rel="'.$res['ord'].'">к заявкам...</a>"';
					shell_exec($msg);
					error_log($msg);
				}
				mysqli_free_result($r);
				*/
			} else {
				$arr = array(
					'state' => 0,
					'msg' => "Почта должна быть в формате xxxx@xxxx.xx",
					'id' => 0,
					'clid' => 0,
				);					
			}
		} else {
			$arr = array(
				'state' => 0,
				'msg' => "Телефон должен быть в формате +#(###) ###-####",
				'id' => 0,
				'clid' => 0,
			);				
		}
	}
	return $arr;
}
?>