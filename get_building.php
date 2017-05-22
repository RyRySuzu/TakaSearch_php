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
	
	$sql = "select * from building where useraccount_id = '$useraccount_id'";
	$stmt = $dbh->query($sql);

	//取得したデータを配列に格納
	while ($row = $stmt->fetchObject())
	{
		$users[] = array(
			'lat'=> $row->lat,
			'lng'=> $row->lng,
			'width'=> $row->width,
			'depth'=> $row->depth,
			'height'=> $row->height,
			'material'=> $row->material,
			'roof'=> $row->roof,
			'topright_lat'=> $row->topright_lat,
			'topleft_lat'=> $row->topleft_lat,
			'bottomright_lat'=> $row->bottomright_lat,
			'bottomleft_lat'=> $row->bottomleft_lat,
			'topright_lng'=> $row->topright_lng,
			'topleft_lng'=> $row->topleft_lng,
			'bottomright_lng'=> $row->bottomright_lng,
			'bottomleft_lng'=> $row->bottomleft_lng,
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