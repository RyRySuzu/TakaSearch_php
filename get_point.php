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
	
	$gamegroup_id = $_POST["gamegroup_id"];
	
	//'users' テーブルのデータを取得する
	$sql = "select point from gameuser where gameuser.gamegroup_id = '$gamegroup_id' order by point desc limit 10";
	$stmt = $dbh->query($sql);
	
	$sql2 = "select useraccount.name from gameuser, useraccount where gameuser.gamegroup_id = '$gamegroup_id' and gameuser.useraccount_id = useraccount.id order by gameuser.point desc limit 10";
	$stmt2 = $dbh->query($sql2);
	
	//取得したデータを配列に格納
	while ($row = $stmt->fetchObject() and $row2 = $stmt2->fetchObject())
	{
		$users[] = array(
			'name'=> $row2->name,
			'point'=> $row->point,
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