<?php

//接続文字列 (PHP5.3.6から文字コードが指定できるようになりました)
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

	//$query= 'where id =1';
	//'users' テーブルのデータを取得する
	
	$sql = "select lat, lng from useraccount where id = '$useraccount_id'";
	$stmt = $dbh->query($sql);
	
	if (!$stmt) {
		die('error'.mysql_error());
	}
	
	$row2 = $stmt->fetchObject();
	
	$lat1 = $row2->lat;
	$lng1 = $row2->lng;
	
	$sql2 = "select lat, lng, id from useraccount";
	$stmt2 = $dbh->query($sql2);
	
	if (!$stmt2) {
		die('error245245'.mysql_error());
	}
	
	$e2 = 0.00669437999019758;
	$a = 6378137;
	$ae = 6335439.32729246;
	
	while ($rows = $stmt2->fetchObject()){
		$lat2 =$rows->lat;
		$lng2 =$rows->lng;
		
		$dy = deg2rad($lat1 - $lat2);
		$dx = deg2rad($lng1 - $lng2);
		$uy = deg2rad(($lat1 + $lat2) / 2);
		$w = pow(1 - $e2 * pow(sin($uy), 2), 0.5);
		$n = $a / $w;
		$m = $ae / pow($w, 3);
		$long = pow( pow($dy * $m, 2) + pow($dx * $n * cos($uy), 2), 0.5);
		
		if($long < 5000){
			$near_user[] = $rows->id;
		}
	}
	
	$sql3 = "select b.useraccount_id, u.name, sum(point) as sum_point from building b inner join useraccount u on b.useraccount_id = u.id where b.useraccount_id in (".implode(",", $near_user).") group by useraccount_id order by sum_point desc limit 10";
	$stmt3 = $dbh->query($sql3);
	
	if (!$stmt3) {
		die('error4444444'.mysql_error());
	}
	
	//取得したデータを配列に格納
	while ($row = $stmt3->fetchObject()){
	
		
		$users[] = array(
			'useraccount_id'=> $row->useraccount_id,
			'name'=> $row->name,
			'sum'=> $row->sum_point,
			);
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