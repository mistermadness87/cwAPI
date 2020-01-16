<?php 
//выборка из таблицы
function tblData($req) {
	global $shop;
	
	$tableRes = array(
		'cars' => 'car_marka',
		'slider_1' => 'slider_1'
	);
	
	if(isset($tableRes[$req['tbl']])){
		
		
		
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
					'tblRows' => array(),
				);			
			}
		}
		if($shown=1){
			$row = mysqli_fetch_assoc($r);
			mysqli_free_result($r);
			$password = "tutu";
			$database = "warehouse";
			$database = "cw_".$row['id'];
			$username = "cwuser_".$row['id'];
			mysqli_free_result($r);
			$shop = mysqli_connect($hostname, $username, $password, $database) or trigger_error(mysqli_error(),E_USER_ERROR);
			$table = new table($shop);
			$res = $table->getData($shop, $tableRes[$req['tbl']], "");
			$arr = array(
				'msg' => $tableRes[$req['tbl']],
				'state' => 1,
				'tblRows' => json_encode($res)
			);				
		}
		
	} else {
		$arr = array(
			'msg' => 'таблица не найдена '.$req['tbl'],
			'state' => 0,
			'tblRows' => array(),
		);		
	}
	
	
	return $arr;
}
?>