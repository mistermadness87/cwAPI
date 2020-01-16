<?php 
function servs($req) {
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
				$wr .= "`Services`.`".mysqli_real_escape_string($shop, $k)."`";
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
		$serarr = array();
		$q = 
		"
		SELECT
			service_types.`name` AS `tpNm`,
			Services.`name`,
			Services.id
		FROM
			Services
		INNER JOIN service_types ON service_types.id = Services.tip
		".$wr."
		";
		//echo $q.'<br />';
		$r = mysqli_query($shop, $q);
		while($row=mysqli_fetch_assoc($r)){
			array_push($serarr, 
				array(
					'id' => $row['id'],
					'name' => $row['name'],
					'tip' => $row['tpNm']
				)
			);		
		}
		mysqli_free_result($r);
		
		$arr = array(
			'state' => 1,
			'msg' => "Список услуг с типами",
			'servouts' => $serarr
		);
	}
	return $arr;
}
?>