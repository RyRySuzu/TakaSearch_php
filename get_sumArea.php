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
	

	//'users' テーブルのデータを取得する
	$sql = "select width, depth from building where useraccount_id = '$useraccount_id'";
	$stmt = $dbh->query($sql);
	if (!$stmt) {
		die('error95648554'.mysql_error());
	}

	$sum = 0;
	foreach ($dbh->query($sql) as $get) {
		if($get[width] > 0 && $get[depth] > 0){
			$sum = $sum + $get[width] * $get[depth];
		}
    }
	
	//取得したデータを配列に格納
	$users[] = array(
		'sum'=> $sum,	
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