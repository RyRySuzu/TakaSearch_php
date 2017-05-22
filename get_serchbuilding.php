<?php

///接続文字列 (PHP5.3.6から文字コードが指定できるようになりました)
$dsn = 'xx';

//ユーザ名
$user = 'xx';

//パスワード
$password = 'xx';

try
{
	//nullで初期化
	$users = null;
	
	//DBに接続
	$dbh = new PDO($dsn, $user, $password);
	
	if (isset($_POST["id"]))
	{
		$query= ' where id =' . $_POST["id"];
	}
	else
	{
		$query="";
	}
	
	$useraccount_id = $_POST["useraccount_id"];
	$walkingspeed = $_POST["walkingspeed"];
	$tsunamitime = $_POST["tsunamitime"];
	$tsunamiheight = $_POST["tsunamiheight"];

	//'users' テーブルのデータを取得する
	$sql = "select lat, lng from useraccount where id = '$useraccount_id'";
	$stmt = $dbh->query($sql);
	
	if (!$stmt) {
		die('error'.mysql_error());
	}
	
	$row2 = $stmt->fetchObject();
	
	$lat1 = $row2->lat;
	$lng1 = $row2->lng;

	$sql2 = "select lat, lng, id from building";
	$stmt2 = $dbh->query($sql2);
	
	if (!$stmt2) {
		die('error245245'.mysql_error());
	}
	
	$believe = $walkingspeed * $tsunamitime * 60;
	
	$e2 = 0.00669437999019758;
	$a = 6378137;
	$ae = 6335439.32729246;
	
	while ($rows = $stmt2->fetchObject()){
		$lat2 =$rows->lat;
		$lng2 =$rows->lng;

		$dy = deg2rad($lat1 - $lat2);
		$dx = deg2rad($lng1 - $lng2);
		$uy = deg2rad($lat1 + $lat2) / 2;
		$w = pow(1 - $e2 * pow(sin($uy), 2), 0.5);
		$n = $a / $w;
		$m = $ae / pow($w, 3);
		$long = pow( pow($dy * $m, 2) + pow($dx * $n * cos($uy), 2), 0.5);
		
		if($long < $believe){
			$near_user[] = $rows->id;
		}
	}
	
	$sql3 = "select lat, lng, height from building where height > '$tsunamiheight' and id in (".implode(",", $near_user).") order by height desc limit 1";
	$stmt3 = $dbh->query($sql3);
	
	if (!$stmt3) {
		$users[] = array(
			'lat'=> 400,
			'lng'=> 400,
		);
	}else{
		$row = $stmt3->fetchObject();
		if($row->lat == Null or $row->lng == Null){
			$users[] = array(
			'lat'=> 400,
			'lng'=> 400,
		);
		}else{
			$users[] = array(
				'lat'=> $row->lat,
				'lng'=> $row->lng,
			);
		}
	}
	
	//JSON形式で出力する
	header('Content-Type: application/json; charset=UTF-8');
	echo json_encode( $users, JSON_UNESCAPED_UNICODE );
	exit;
}
catch (PDOException $e)
{
	//例外処理
	die('Error:' . $e->getMessage());
}