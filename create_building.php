<?php

//Ajax通信ではなく、直接URLを叩かれた場合はエラーメッセージを表示
//if (
//	  !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
//	  && (!empty($_SERVER['SCRIPT_FILENAME']) && 'json.php' === basename($_SERVER['SCRIPT_FILENAME']))
//	  ) 
//{
//	  die ('このページは直接ロードしないでください。');
//}


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
	$postlat = $_POST["lat"];
	$postlng = $_POST["lng"];
	$postheight = $_POST["height"];
	$postwidth = $_POST["width"];
	$postdepth = $_POST["depth"];
	$postroof = $_POST["roof"];
	$postmaterial = $_POST["material"];
	//$postcomment = $_POST["comment"];
	$posttopright_lat = $_POST["topright_lat"];
	$posttopleft_lat = $_POST["topleft_lat"];
	$postbottomright_lat = $_POST["bottomright_lat"];
	$postbottomleft_lat = $_POST["bottomleft_lat"];
	$posttopright_lng = $_POST["topright_lng"];
	$posttopleft_lng = $_POST["topleft_lng"];
	$postbottomright_lng = $_POST["bottomright_lng"];
	$postbottomleft_lng = $_POST["bottomleft_lng"];

	$sql = "INSERT INTO building 
		(useraccount_id,
		 lat,
		 lng,
		 width,
		 depth,
		 height,
		 material,
		 roof,
		 point,
		 comment,
		 topright_lat,
		 topleft_lat,
		 bottomright_lat,
		 bottomleft_lat,
		 topright_lng,
		 topleft_lng,
		 bottomright_lng,
		 bottomleft_lng)
		 VALUES
		 ('$useraccount_id',
		 '$postlat',
		 '$postlng',
		 '$postwidth',
		 '$postdepth',
		 '$postheight',
		 '$postmaterial',
		 '$postroof',
		 '$postpoint',
		 '$postcomment',
		 '$posttopright_lat',
		 '$posttopleft_lat',
		 '$postbottomright_lat',
		 '$postbottomleft_lat',
		 '$posttopright_lng',
		 '$posttopleft_lng',
		 '$postbottomright_lng',
		 '$postbottomleft_lng')";
		 
    $result_flag = $dbh->query($sql);
	
	if (!$result_flag) {
		die('error'.mysql_error());
	}
	
}
catch (PDOException $e)
{
	//例外処理
	die('Error:' . $e->getMessage());
}