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
	
    $sql2 = "UPDATE useraccount SET lat = '$postlat', lng = '$postlng', participate = '$postparticipate' WHERE id = '$useraccount_id'";
	$result_flag = $dbh->query($sql2);

	if (!$result_flag) {
    die('error565'.mysql_error());
	}
	
	//'users' テーブルのデータを取得する
	$sql8 = "select ac2.id from useraccount ac1,useraccount ac2 where ac2.participate = true and ac1.id = '$useraccount_id' AND ac1.id != ac2.id AND POWER(POWER(ac1.lat - ac2.lat, 2) + POWER(ac1.lng - ac2.lng, 2), 0.5) < 0.001;";
	$stmt = $dbh->query($sql8);
	
	if (!$stmt) {
    die('error64123'.mysql_error());
	}

	$all = $stmt->fetchAll();
	$gamegroup_id = NULL;
	
	if (count($all) > 0){

		$all2 = $all[0];
		$gameaccount_id = $all2[0];

		$sql = "select * from gameuser where useraccount_id = '$gameaccount_id' order by id desc limit 1";
		$stmt3 = $dbh->query($sql);
		if (!$stmt3) {
		die('error95648'.mysql_error());
		}
		$gameuser = $stmt3->fetchObject();
		$gamegroup_id = $gameuser->gamegroup_id;
		
	} else {
	$create_group = "INSERT INTO gamegroup (id, lat, lng, datetime, Enable) VALUES ('',  '$postlat', '$postlng' ,'$nowdatetime', 'true')";
	$stmt2 = $dbh->query($create_group);
	if (!$stmt2) {
	die('error3'.mysql_error());
	}
	$gamegroup_id = $dbh->lastInsertId();
	}
	
	$c_gameuser = "INSERT INTO gameuser (gamegroup_id, useraccount_id) VALUES ('$gamegroup_id', '$useraccount_id')";
	$result_flag2 = $dbh->query($c_gameuser);
	
	if (!$result_flag2) {
	die('error2'.mysql_error());
	}
	
	$result[] = array(
			'gamegroup_id' => $gamegroup_id,
			);
	
	if ($gamegroup_id = 0 or $gamegroup_id = NULL) {
	die('error'.mysql_error());
	}
	
	//JSON形式で出力する
	header('Content-Type: application/json; charset=UTF-8');
	echo json_encode( $result, JSON_UNESCAPED_UNICODE );
	exit;
}
catch (PDOException $e)
{
	//例外処理
	die('Error:' . $e->getMessage());
}