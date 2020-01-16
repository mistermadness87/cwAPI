<?php 
//выборка из таблицы
function price($req) {
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
		$wr = "";
		$reqChange = array(
			'klass' => "car_class.`id`",
			'name' => "Services.`id`",
			'typeName' => "service_types.`id`",
		);
		if($req['param']!=''){
			//есть условие
			parse_str($req['param'], $par);
			$i = 0;
			foreach($par AS $k => $v){
				if($i==0){
					$wr .= " WHERE ";
				} else {
					$wr .= " AND ";
				}
				if(!isset($reqChange[$k])){
					$wr .= "`".mysqli_real_escape_string($shop, $k)."`";
				} else {
					$wr .= $reqChange[$k];
				}
				if(is_array($v)){
					$ii = 0;
					$wr .= " IN (";
					for($ii=0;$ii<sizeof($v);$ii++){
						if($ii!=0){
							$wr .= ", ";
						}
						$wr .= "'".$v[$ii]."'";
					}
					$wr .= ")";
				} else {
					$wr .= " = '".mysqli_real_escape_string($shop, $v)."'";
				}
				$i++;
			}
		}
		if(isset($req['model'])){
			if($req['model']!=''){
				$q =
				"
				SELECT
					cars.klas
				FROM
					cars
				WHERE
					cars.id = '".mysqli_real_escape_string($shop, $req['model'])."'
				";
				$r = mysqli_query($shop, $q);
				$row = mysqli_fetch_assoc($r);
				mysqli_free_result($r);
				if($wr==''){
					$wr .= " WHERE ";
				} else {
					$wr .= " AND ";
				}
				$wr .= "`car_class`.`id` = '".$row['klas']."'";
			}
		}
		$q = 
		"
		SELECT
			Services.`name`,
			Services.kod,
			service_mod_pr_time.cena,
			service_mod_pr_time.prodolzhitelnost,
			car_class.`name` AS `klass`,
			service_types.`name` AS typeName
		FROM
			service_mod_pr_time
		INNER JOIN 
			Services ON Services.id = service_mod_pr_time.usluga
		INNER JOIN 
			car_class ON car_class.id = service_mod_pr_time.avto
		INNER JOIN 
			service_types ON service_types.id = Services.kategoriya_uslugi
		".$wr."
		ORDER BY 
			Services.`name`,
			 `klass`
		";
		$res = array();
		$r = mysqli_query($shop, $q);
		$i = 0;
		while($row=mysqli_fetch_assoc($r)){
			$res[$i] = array(
				'name' => $row['name'],
				'kod' => $row['kod'],
				'cena' => $row['cena'],
				'prodolzhitelnost' => $row['prodolzhitelnost'],
				'klass' => $row['klass'],
				'typeName' => $row['typeName']
			);
			$i++;
		}
		mysqli_free_result($r);
		$arr = array(
			'state' => 1,
			'msg' => 'список услуг для авто или класса',
			'priceels' => $res
		);				
	}
	
	return $arr;
}
?>