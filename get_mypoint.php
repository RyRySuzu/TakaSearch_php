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
	$useraccount_id = $_POST["useraccount_id"];
	
	//'users' テーブルのデータを取得する
	$sql = "select point from gameuser where gameuser.gamegroup_id = '$gamegroup_id' order by point desc";
	$stmt = $dbh->query($sql);
	
	$sql3 = "select point from gameuser where gameuser.gamegroup_id = '$gamegroup_id' and gameuser.useraccount_id = '$useraccount_id' order by point desc";
	$stmt3 = $dbh->query($sql3);
	
	$row3 = $stmt3->fetchObject();
	
	$count = 1;
	
	foreach ($dbh->query($sql) as $get) {
		if($row3 -> point < $get[point]){
			$count = $count + 1;
		}
    }
	
	$sql2 = "select name from useraccount where id = $useraccount_id";
	$stmt2 = $dbh->query($sql2);
	$row2 = $stmt2->fetchObject();
	
	//取得したデータを配列に格納
		$users[] = array(
			'myname'=> $row2->name,
			'mypoint'=> $row3->point,
			'count'=> $count
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