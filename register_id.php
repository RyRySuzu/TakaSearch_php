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
	
	$postname = $_POST["name"];
	
	//テーブルにデータを挿入
	$playername = "INSERT INTO useraccount (id, name, lat, lng, registdatetime, participate) VALUES ('', '$postname', '0', '0' ,'0', '0')";
	$stmt2 = $dbh->query($playername);
	
	$id = $dbh->lastInsertId();
	
	//テーブルのデータを取得する
	$sql = "select id from useraccount  where id = '$id'";
	$stmt = $dbh->query($sql);
	
	//取得したデータを配列に格納
	while ($row = $stmt->fetchObject())
	{
		$users[] = array(
			'id'=> $row->id,
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