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
	$postlat = $_POST["lat"];
	$postlng = $_POST["lng"];
	$postparticipate = $_POST["participate"];
	
	$nowdatetime = date( 'Y-m-d H:i');
	
	$sql2 = "UPDATE useraccount SET lat = '$postlat', lng = '$postlng', registdatetime = '$nowdatetime',participate = '$postparticipate' WHERE id = '$useraccount_id'";
    $result_flag = $dbh->query($sql2);
	
	if (!$result_flag) {
    die('error'.mysql_error());
	}
	
	//'users' テーブルのデータを取得する
	$sql = 'select * from useraccount ' . $query;
	$stmt = $dbh->query($sql);
	
	//取得したデータを配列に格納
	while ($row = $stmt->fetchObject())
	{
		$users[] = array(
			'id'=> $row->id,
			'name' => $row->name,
			'registdatetime' => $row->registdatetime
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