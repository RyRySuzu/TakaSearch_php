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
	
	//'users' テーブルのデータを取得する
	$sql = "select id from gameuser where gamegroup_id = '$gamegroup_id'";
	$stmt = $dbh->query($sql);
	
	$sql3 = "select Endtime from game where gamegroup_id = '$gamegroup_id' order by id desc limit 1";
	$stmt3 = $dbh->query($sql3);
	
	if (!$stmt3) {
		die('error95648'.mysql_error());
	}
	$rows2 = $stmt3->fetchObject();

	if($rows2->Endtime == 0){
		$goal_rand = mt_rand(0, 15);

		switch($goal_rand){
		case 0:
			$endtime = 300 ;
			$goalhight = 5;	break;
		case 1:
			$endtime = 330 ;
			$goalhight = 6;	break;
		case 2:
			$endtime = 360 ;
			$goalhight = 7;	break;
		case 3:
			$endtime = 390 ;
			$goalhight = 8;	break;
		case 4:
			$endtime = 420 ;
			$goalhight = 9;	break;
		case 5:
			$endtime = 450 ;
			$goalhight = 10;	break;
		case 6:
			$endtime = 480 ;
			$goalhight = 11;	break;
		case 7:
			$endtime = 510 ;
			$goalhight = 12;	break;
		case 8:
			$endtime = 540 ;
			$goalhight = 13;	break;
		case 9:
			$endtime = 570 ;
			$goalhight = 14;	break;
		case 10:
			$endtime = 600 ;
			$goalhight = 15;	break;
		case 11:
			$endtime = 630 ;
			$goalhight = 16;	break;
		case 12:
			$endtime = 660 ;
			$goalhight = 17;	break;
		case 13:
			$endtime = 690 ;
			$goalhight = 18;	break;
		case 14:
			$endtime = 720 ;
			$goalhight = 19;	break;
		case 15:
			$endtime = 750 ;
			$goalhight = 20;	break;
		}
		$sql4 = "UPDATE game SET Endtime = '$endtime', goalhight = '$goalhight' WHERE gamegroup_id = '$gamegroup_id'";
		$result_flag4 = $dbh->query($sql4);
		
		if (!$result_flag4) {
		die('error'.mysql_error());
		}
	}
	
	$sql5 = "select * from game WHERE gamegroup_id = '$gamegroup_id' order by id desc limit 1";
	$stmt5 = $dbh->query($sql5);
	
	$row2 = $stmt5->fetchObject();
	
	//取得したデータを配列に格納
	while ($row = $stmt->fetchObject())
	{
		$count[] = array(
			'0'=> $row->id,
			);
	}
	
	
	if(1 < count($count)){
		$users[] = array(
		'count'=> 1,
		'Endtime' => $row2->Endtime,
		'goalhight' => $row2->goalhight
		);
	}else{
		$users[] = array(
		'count'=> 0,
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