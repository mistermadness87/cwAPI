<?php 
//выборка из таблицы
function social($socReq) {
	global $shop;
	global $password;
	global $hostname;
	
	$q = "SELECT `id` FROM `cardsSubscr_mail` WHERE `hash` = '".mysqli_real_escape_string($shop, $socReq['auth'])."abebe'";
	$r = mysqli_query($shop, $q);
	$shown = 1;
	if(mysqli_num_rows($r)==0){
		mysqli_free_result($r);
		$q = "SELECT `id` FROM `cardsSubscr_mail` WHERE `hash` = '".mysqli_real_escape_string($shop, $socReq['auth'])."'";
		$r = mysqli_query($shop, $q);
		if(mysqli_num_rows($r)==0){
			$shown = 0;
			$arr = array(
				'msg' => 'пользователь не найден '.$q,
				'state' => 0,
				'classes' => array(),
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
		if($socReq['param']!=''){
			//есть условие
			parse_str($socReq['param'], $par);
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
		$res = $table->getData($shop, "social", $wr, "num");
		$arr = array(
			'state' => 1,
			'msg' => 'социальные сети',
			'socits' => $res
		);				
	}
	
	return $arr;
}
?>