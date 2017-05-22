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
	$walkingspeed = $_POST["walkingspeed"];
	$postlat = $_POST["lat"];
	$postlng = $_POST["lng"];
	$travellingtime = $_POST["travellingtime"];

	//'users' テーブルのデータを取得する
	$sql = "select * from building where POWER(POWER(ac1.lat - ac2.lat, 2) + POWER(ac1.lng - ac2.lng, 2), 0.5) < 0.001; order by point asc limit 1";
	$stmt = $dbh->query($sql);

	//取得したデータを配列に格納
	while ($row = $stmt->fetchObject())
	{
		$users[] = array(
			'id'=> $row->id
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