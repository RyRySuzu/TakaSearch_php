<?php

//接続文字列 (PHP5.3.6から文字コードが指定できるようになりました)
$dsn = 'xx';

//ユーザ名
$user = 'xx';

//パスワード
$password = 'xx';s

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
	$sql = "select id from building where useraccount_id = '$useraccount_id'";
	$stmt = $dbh->query($sql);
	
	if (!$stmt) {
    die('error565'.mysql_error());
	}
	
	//取得したデータを配列に格納
	
	while ($row = $stmt->fetchObject())
	{
		$count[] = array(
			'0'=> $row->id,
			);
	}
	
	$count_building = count($count);

	
	$users[] = array(
		'count_building'=> $count_building,
	);
		
	
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