<?php 
function servpr($req) {
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
		$row = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		$database = "cw_".$row['id'];
		$username = "cwuser_".$row['id'];
		$shop = mysqli_connect($hostname, $username, $password, $database) or trigger_error(mysqli_error(),E_USER_ERROR);
		
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/user/user.php');
		$user = new user($shop);
		
		require_once($_SERVER['DOCUMENT_ROOT'].'/cms/classes/ord/order.php');
		$ord = new order($shop);
		$setup = $ord->setup();
		$setup = array_map('trim', $setup);
		$id = $req['clid'];
		if($id==0){
			//пользователь не задан явно
			if($req['phone']!=''){
				$vph = validateNumberMyOwn($req['phone']);
				if($vph){
					//телефон задан и верен формату, поищем пользователя
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
				} else {
					$arr = array(
						'state' => 0,
						'msg' => "Телефон должен быть в формате +#(###) ###-####",
						'clid' => 0,
						'servouts' => array()
					);
					return $arr;
					exit();
				}
			}
			if($req['mail']!=''){
				$vph = validateEmailMyOwn($req['mail']);
				if($vph){
					//почта задана и формат верен, поищем пользователя
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
				} else {
					$arr = array(
						'state' => 0,
						'msg' => "Почта должна быть в формате xxxx@xxxx.xx",
						'clid' => 0,
						'servouts' => array()
					);
					return $arr;
					exit();
				}
			}
		}
		//выше мы искали всеми способами клиента, если id остался равен 0, то будет общая цена
		$table = new table($shop);
		
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
		
		$dis = $req['disc'];

		$dis += $d;
		$qtyarr = array();
		$con = "";
		for($i=0;$i<sizeof($req['servspr']);$i++){
			if($i!=0){
				$con .= ", ";
			}
			$con .= "'".mysqli_real_escape_string($shop, $req['servspr'][$i]['id'])."'";
			$qtyarr[$req['servspr'][$i]['id']] = $req['servspr'][$i]['qty'];
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
					'price' => $pr * $qtyarr[$row['id']],
					'last' => $row['prodolzhitelnost'] * $qtyarr[$row['id']],
				)
			);			
		}
		mysqli_free_result($r);
		$arr = array(
			'state' => 1,
			'msg' => "Список услуг по условию модель авто и клиент ",
			'clid' => $id,
			'servsprout' => $serarr
		);
	}
	return $arr;
}
?>