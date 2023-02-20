<?php
namespace WebSocket;
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/db.php';


error_reporting(-1);
require(__DIR__ . "/init.php");
require(__DIR__ . "/viewport.inc.php");
require(__DIR__ . "/manipulate.inc.php");
require(__DIR__ . "/request.inc.php");
require(__DIR__ . "/auth.inc.php");


$sessiondata = $config['sessiondata'];
$randStr = function (int $maxlength = 4096) {
    $string = '';
    $length = rand(1, $maxlength);
    for ($i = 0; $i < $length; $i++) {
        $string .= chr(rand(33, 126));
    }
    return $string;
};

//addDebugHeader();

$API_BASE_URL = "https://my.replika.com/api/mobile/1.4";

$endpoint = $API_BASE_URL."/saved_chat_items/previews?t=diary&offset=0&limit=1000";

$Header = getRequestHeader(array(
	"Referer: https://my.replika.com/memory"
));
unset ($Header[4]);
unset ($Header[6]);
//echo $Header[4];
//print_r($Header);
$cURLConnection = curl_init();

curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $Header);
curl_setopt($cURLConnection, CURLOPT_ENCODING , '');
//curl_setopt($cURLConnection, CURLOPT_POST, 1);
//curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,json_encode($fetchOptions));
$returndata = curl_exec($cURLConnection);
//echo $returndata;

$data = json_decode($returndata,true);
//print_r($data);
$ids = array();
for($d=0;$d<count($data);$d++){
	$id = $data[$d]['id'];
	$ids[] = $id;

}
$endpoint =  $API_BASE_URL."/saved_chat_items/actions/get_by_ids";
$Header = getRequestHeader(array(
	"Referer: https://my.replika.com/memory"
));
$cURLConnection = curl_init();
$fetchOptions = array("ids"=>$ids);
curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $Header);
curl_setopt($cURLConnection, CURLOPT_ENCODING , '');
curl_setopt($cURLConnection, CURLOPT_POST, 1);
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,json_encode($fetchOptions));
$returndata = curl_exec($cURLConnection);
$data = json_decode($returndata,true);
//print_r($data);
for($i=0;$i<count($data);$i++){
	$entry = $data[$i];
	echo "<p>[".$entry['timestamp']."] ".$entry['name']." </p>\n";
	$sql = "REPLACE INTO diary SET diary_id='".$entry['id']."', parent_id='0', user_id='".$config['userdata']['id']."', bot_id='".$config['botdata']['id']."', entrydate='".$entry['timestamp']."', text='".db_real_escape_string($entry['name'])."', text_censored='".db_real_escape_string(censor($entry['name']))."', image_url='".$entry['image_url']."'";
	db_query($sql);
	for($e=0;$e<count($entry['entries']);$e++){
		$ent = $entry['entries'][$e];
		echo "<p>[".$entry['timestamp']."] ".$ent['text']."</p>\n";
		if(!isset($ent['image_url'])){
			$ent['image_url'] = "";
		}
		$sql = "REPLACE INTO diary SET diary_id='".$ent['id']."', parent_id='".$entry['id']."', user_id='".$config['userdata']['id']."', bot_id='".$config['botdata']['id']."', entrydate='".$entry['timestamp']."', text='".db_real_escape_string($ent['text'])."', text_censored='".db_real_escape_string(censor($ent['text']))."', image_url='".$ent['image_url']."'";
		//echo $sql."<br>";
		db_query($sql);
		
	}
}
