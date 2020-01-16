<?php 
function fastrec($req) {
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
			if($id==0){
				$ur = $user->register($setup, $req['phone'], '', $req['name'], '', 0, 0);
				$id = $ur['id'];
				$q = "INSERT INTO `user_cars` (`kontragent`, `mashina`) VALUES ('".$id."', '".mysqli_real_escape_string($shop, $req['model'])."')";
				mysqli_query($shop, $q);
			} else {
				//если мы нашли пользователя, то поищем у него машину
				$q = "SELECT `id` FROM `user_cars` WHERE `kontragent` = '".$id."' AND `mashina` = '".mysqli_real_escape_string($shop, $req['model'])."'";
				$r = mysqli_query($shop, $q);
				if(mysqli_num_rows($r)==0){
					$row = mysqli_fetch_assoc($r);
					$q = "INSERT INTO `user_cars` (`kontragent`, `mashina`) VALUES ('".$id."', '".mysqli_real_escape_string($shop, $req['model'])."')";
					mysqli_query($shop, $q);
				}
				mysqli_free_result($r);
			}
			
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
				$msgAPI = 'новая заявка на запись';
				$msg = '/usr/bin/curl '.$setup[32].'://'.$setup[8].'/pub/?id=order_'.$row['id'].' --insecure -d "'.$msgAPI.'<br /><a href="#" class=\'zayavreq\' rel="'.$res['ord'].'">к заявкам...</a>"';
				shell_exec($msg);
				error_log($msg);
			}
			mysqli_free_result($r);
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