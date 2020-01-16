<?php 
//выборка из таблицы
function boxes($req) {
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
				'msg' => 'пользователь не найден '.$q,
				'state' => 0,
				'marks' => array(),
			);			
		}
	}
	if($shown=1){
		$row = mysqli_fetch_assoc($r);
		mysqli_free_result($r);
		$database = "cw_".$row['id'];
		$username = "cwuser_".$row['id'];
		$shop = mysqli_connect($hostname, $username, $password, $database) or trigger_error(mysqli_error(),E_USER_ERROR);
		$table = new table($shop);
		$wr = "";
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
				$wr .= "`".mysqli_real_escape_string($shop, $k)."`";
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
		//$res = $table->getData($shop, "service_types", $wr);
		$q = 
		"
		SELECT
			boxes.`id`,
			carwashes.`name` AS `cw`,
			boxes.`name`,
			boxes.otkrytie,
			boxes.zakrytie
		FROM
			boxes
		INNER JOIN carwashes ON carwashes.id = boxes.avtomoyka
		".$wr."
		";
		$res = array();
		$r = mysqli_query($shop, $q);
		$i = 0;
		while($row=mysqli_fetch_assoc($r)){
			$q = 
			"
			SELECT
				service_types.`name`
			FROM
				service_types_box
			INNER JOIN service_types ON service_types.id = service_types_box.tip_uslugi
			WHERE
				service_types_box.boks = '".$row['id']."'
			";
			$r2 = mysqli_query($shop, $q);
			$sers = array();
			$ii = 0;
			while($row2=mysqli_fetch_assoc($r2)){
				$sers[$ii]['name'] = $row2['name'];
				$ii++;
			}
			mysqli_free_result($r2);
			$res[$i] = array(
				'id' => $row['id'],
				'name' => $row['name'],
				'carwash' => $row['cw'],
				'otkrytie' => $row['otkrytie'],
				'zakrytie' => $row['zakrytie'],
				'ser' => $sers
			);
			$i++;
		}
		mysqli_free_result($r);
		$arr = array(
			'state' => 1,
			'msg' => 'список боксов автомойки с услугами',
			'boxes' => $res
		);				
	}
	
	return $arr;
}
?>