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
	
	$postpoint = $_POST["point"];
	$useraccount_id = $_POST["useraccount_id"];
	$gamegroup_id = $_POST["gamegroup_id"];
	
	$sql2 = "UPDATE gameuser SET point = '$postpoint' WHERE useraccount_id = '$useraccount_id' and gamegroup_id = '$gamegroup_id'";
    $result_flag = $dbh->query($sql2);
	
}
catch (PDOException $e)
{
	//例外処理
	die('Error:' . $e->getMessage());
}