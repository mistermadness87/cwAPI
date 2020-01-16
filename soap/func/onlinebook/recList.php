<?php 
//выборка из таблицы
function recList($req) {
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
			`book_orders`.`date` 
		BETWEEN 
			'".date('Y-m-d 00:00:00', strtotime($req['date1']))."' AND 
			'".date('Y-m-d 23:59:59', strtotime($req['date2']))."' 
		";
		
		if(isset($req['client'])){
			$wr .= " AND `book_orders`.`kontragent` = '".mysqli_real_escape_string($shop, $req['client'])."' ";
		}
		
		$q = 
		"
		SELECT
			book_orders.id,
			book_orders.date,
			book_orders.`kontragent`,
			ord_states.`name`
		FROM
			book_orders
		INNER JOIN 
			ord_states ON ord_states.id = book_orders.`status`
		".$wr."
		ORDER BY 
			book_orders.id ASC
		";
		$res = array();
		$r = mysqli_query($shop, $q);
		$i = 0;
		while($row=mysqli_fetch_assoc($r)){
			$res[$i] = array(
				'id' => $row['id'],
				'date' => $row['date'],
				'client' => $row['kontragent'],
				'status' => $row['name']
			);
			$i++;
		}
		mysqli_free_result($r);
		$arr = array(
			'state' => 1,
			'msg' => 'список записей за выбранную дату ',
			'recLits' => $res
		);				
	}
	
	return $arr;
}
?>