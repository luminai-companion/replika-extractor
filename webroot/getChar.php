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


$API_BASE_URL = "https://my.replika.com/api/mobile/1.4";

$endpoint = $API_BASE_URL."/3d_avatar_v2/binaries/web?version=171";


$Header = getRequestHeader(array(
	"Referer: https://my.replika.com/memory"
));
unset ($Header[4]);
unset ($Header[6]);
//echo $Header[4];
//print_r($Header);


$assetDir = $_SERVER['DOCUMENT_ROOT']."/replika/assets/";
if(!is_dir($assetDir)){
	mkdir ($assetDir, 0777);
}
$assetDir .= "character_model/";
if(!is_dir($assetDir)){
	mkdir ($assetDir, 0777);
}

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


addDebugHeader();

//print_r($data);
for($i=0;$i<count($data['bundles']);$i++){
	$model = $data['bundles'][$i];
	$dir = $assetDir.$model['id']."/";
	if(!is_dir($dir)){
		mkdir ($dir, 0777);
	}
	
	echo "=== ID: ".$model['id']."\n";
	echo "=== URL: ".$model['url']."\n";
	echo "=== DIR: ".$dir."\n";
	if(!is_file($dir.basename($model['url']))){
		$content = file_get_contents($model['url']);
		file_put_contents($dir.basename($model['url']), $content);
	}
}
/*
?>
<table width="500" cellspacing=0 cellpadding=1 style="border: black thin solid;">
	<tr>
		<td align="left"><b>Relation</b></td><td align="left"><b>Name</b></td>
	</tr><?php
for($i=0;$i<count($persons);$i++){
	$person = $persons[$i];
	$sql = "REPLACE INTO persons SET user_id='".$config['userdata']['id']."', relation='".$person['relation']."', name='".$person['name']."'";
	db_query($sql);
	
	?>
	<tr>
		<td align="left"><?php echo $person['relation'];?></td><td align="left"><?php echo $person['name'];?></td>
	</tr>
<?php
}
?></table><?php

?>
<p><b>Facts</b></p>
<ul>
<?php
for($i=0;$i<count($facts);$i++){
	$fact = $facts[$i];
	//user_id	bot_id	text	text_censored

	$sql = "REPLACE INTO memory SET user_id='".$config['userdata']['id']."',bot_id='".$config['botdata']['id']."', text='".db_real_escape_string($fact['text'])."', text_censored='".db_real_escape_string(censor($fact['text']))."'";
	db_query($sql);
	?>
	<li><?php echo $fact['text'];?></li>
<?php
	$fact['text_censored'] = censor($fact['text']);
}
?></ul><?php
*/


addFooter();
