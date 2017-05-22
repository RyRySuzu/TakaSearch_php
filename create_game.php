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
	$gamegroup_id = $_POST["gamegroup_id"];
	$startdatetime = date( 'YmdHis', strtotime( '+10 seconds' ) );
	
	$sql = "select id from game where gamegroup_id = '$gamegroup_id' order by id desc limit 1";
	$stmt = $dbh->query($sql);
	
	$sql2 = "UPDATE useraccount SET participate = 0 WHERE id = '$useraccount_id'";
	$result_flag = $dbh->query($sql2);
	
	if (!$stmt) {
		die('error95648'.mysql_error());
	}
	$rows = $stmt->fetchObject();
	
	if($rows == 0){
		$sql2 = "INSERT INTO game (gamegroup_id, Starttime) VALUES ('$gamegroup_id',  $startdatetime)";
		$result_flag = $dbh->query($sql2);
		
		if (!$result_flag) {
		die('error'.mysql_error());
		}
	}
	

	//'users' テーブルのデータを取得する	
	$sql3 = "select * from game WHERE gamegroup_id = '$gamegroup_id' order by id desc limit 1";
	$stmt3 = $dbh->query($sql3);
	
	while ($row = $stmt3->fetchObject())
	{
	$users[] = array(
			'Starttime' => intval($row->Starttime)
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