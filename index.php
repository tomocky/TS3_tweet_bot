<?php
// Consumer key を入力してください
$consumer_key = "XXXXXXXXXXXXXXXXXXXX";
// Consumer secret を入力してください
$consumer_secret = "YYYYYYYYYYYYYYYYYYYYYYYY";
// Access token を入力してください
$access_token = "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ";
// Access token secret を入力してください
$access_token_secret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX ";
//TS3 server id を入力してください
$ts3_id = "serveradmin";
//TS3 server password を入力してください
$ts3_pass = "password"

$message = "". date("g時i分") ."\n　TS3サーバには　" . $count . "　人がログイン中ですよ。";

//TS3サーバログイン
require_once("libraries/TeamSpeak3/TeamSpeak3.php");
TeamSpeak3::init();

header('Content-Type: text/html; charset=utf8');


//初期化
$status = "offline";
$count = 0;
$max = 0;


//TS3サーバへログインし、オンライン状況、オンライン中の人数の取得処理
try {
    $ts3 = TeamSpeak3::factory("serverquery://" . $ts3_id . ":" . $ts3_pass . "@127.0.0.1:10011/?server_port=9987&use_offline_as_virtual=1&no_query_clients=1");
    $status = $ts3->getProperty("virtualserver_status");
    $count = $ts3->getProperty("virtualserver_clientsonline") - $ts3->getProperty("virtualserver_queryclientsonline");
}
catch (Exception $e) {
    echo '<div style="background-color:red; color:white; display:block; font-weight:bold;">QueryError: ' . $e->getCode() . ' ' . $e->getMessage() . '</div>';
}



//TS3サーバが稼働してない状態時のみ表示
	if($status == "offline"){
		$message = "TS3サーバ停止中";	
	}

//twitter書き込み
//OAuthスクリプトの読み込み
require_once('twitteroauth/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;
 
	if($count != 0){
// つぶやく
		$connection = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret); 
		$req = $connection->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json","POST",array("status"=> $message ));
	}
?>




