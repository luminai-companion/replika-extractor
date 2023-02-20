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



$url = "wss://ws.replika.ai/v17";



$username = $config['userdata']['first_name'];
$repname = $config['botdata']['name'];





$chat_id = $config['sessiondata']['chat_id'];
$token = GUID();
$user_id = $config['sessiondata']['user_id'];
$auth_token = $config['sessiondata']['auth_token'];
$device_id = $config['sessiondata']['device_id'];
$bot_id = $config['botdata']['id'];

$INIT = '{"event_name": "history","payload": {"chat_id": "'.$chat_id.'","limit": 1000},"token": "'.$token.'","auth": {"user_id": "'.$user_id.'","auth_token": "'.$auth_token.'","device_id": "'.$device_id.'"}}';
$php_dict = json_decode($INIT,true);
$raw_dict = $php_dict;
$raw_dict['payload']['last_message_id'] = "";

// If debug mode and logger is available
if (isset($options['debug']) && class_exists('WebSocket\EchoLog')) {
    $logger = new EchoLog();
    $options['logger'] = $logger;
    echo "> Using logger\n";
}
$loop1 = true;
?><pre><?php
// Main loop
$options = [
    'uri'           => $url,
    'timeout'       => rand(1, 60),
    'fragment_size' => rand(1, 4096) * 8,
];
$init = $php_dict;
while ($loop1) {
    try {
        $client = new Client($options['uri'], $options);
        $info = json_encode([
            'uri'           => $options['uri'],
            'timeout'       => $options['timeout'],
            'framgemt_size' => $client->getFragmentSize(),
        ]);
        //echo "> Creating client \n";
		$client->text(json_encode($init));
		flush();
		//echo "> Reading content\n";
		$received = $client->receive();
		$rec = json_decode($received,true);
		message_walk($rec['payload']['messages'],true,true);
		reaction_walk($rec['payload']['message_reactions'],true,true);
		//print_r(json_decode($received,true));
		flush();
		if(count($rec['payload']['messages'])>0){
			$last_message_id = $rec['payload']['messages'][0]['id'];
			$init = $raw_dict;
			$init['payload']['last_message_id'] = $last_message_id;
		}
		else{
			echo "<h2>Import is done. No more messages are waiting on the Replika server.</h2>\n";
			$loop1 = false;
		}
		//exit();
    } catch (\Throwable $e) {
        echo "ERROR: {$e->getMessage()} [{$e->getCode()}]\n";
    }
    sleep(rand(1, 5));
	//exit();
}

function message_walk($messages,$output=true,$database = true){
	global $loop1;
	for($i=0;($i<count($messages) AND $loop1);$i++){
		$message = process_message($messages[$i]);
		if($output){
			echo $message['Chat_From']." (".$message['Chat_Timestamp']."): ".$message['Chat_Text']."\n";
		}
		if($database){
			$sql = "SELECT * FROM chat_history WHERE Chat_ID='".$message['Chat_ID']."'";
			$r = db_query($sql);
			if($r && db_num_rows($r) > 0){
				//already imported
				$sql = "UPDATE chat_history SET User_ID='".$message['User_ID']."', Bot_ID='".$message['Bot_ID']."', Chat_From='".$message['Chat_From']."', Chat_Text='".db_real_escape_string($message['Chat_Text'])."', Chat_Text_censored='".db_real_escape_string($message['Chat_Text_censored'])."', Chat_Type='".$message['Chat_Type']."', Chat_Timestamp='".$message['Chat_Timestamp']."' WHERE Chat_ID='".$message['Chat_ID']."'";
				db_query($sql);
				echo "We already imported this the message ".$message['Chat_ID']."\n";
				$loop1 = true;
				//exit();
			}
			else{
				$sql = "INSERT INTO chat_history SET Chat_ID='".$message['Chat_ID']."', User_ID='".$message['User_ID']."', Bot_ID='".$message['Bot_ID']."', Chat_From='".$message['Chat_From']."', Chat_Text='".db_real_escape_string($message['Chat_Text'])."', Chat_Text_censored='".db_real_escape_string($message['Chat_Text_censored'])."', Chat_Type='".$message['Chat_Type']."', Chat_Timestamp='".$message['Chat_Timestamp']."'";
				db_query($sql);
			}
		}
	}
}
function reaction_walk($reactions,$output=true,$database = true){
	global $loop1;

	for($i=0;($i<count($reactions) AND $loop1);$i++){
		$reaction = process_reaction($reactions[$i]);
		$message_id = $reaction['message_id'];
		if($output){
			echo "Reaction ".$reaction['reaction']." for message: \n";
			echo $reaction['Chat_From']." (".$reaction['Chat_Timestamp']."): ".$reaction['Chat_Text_censored']."\n";
			//print_r($reaction);
		}
		if($database){
			$sql = "SELECT * FROM chat_history WHERE Chat_ID='".$reaction['Chat_ID']."'";
			$r = db_query($sql);
			if($r && db_num_rows($r) > 0){
				//already imported
			}
			else{
				$sql = "UPDATE chat_history SET Chat_Reaction='".$reaction['reaction']."' WHERE Chat_ID='".$message['Chat_ID']."'";
				db_query($sql);
			}
		}
	}
}
function process_message($message){
	global $username,$repname,$user_id,$chat_id,$bot_id;
	global $chat_conversation;
	//print_r($message);
	$m['Chat_From'] = $message['meta']['nature'] == "Robot"? "bot":"user";
	$m['Chat_Text'] = $message['content']['text'];
	$m['Chat_Type'] = $message['content']['type'];
	if($m['Chat_Type'] == "voice_message" AND $m['Chat_From'] == "user" AND trim($m['Chat_Text']) == ""){
		$m['Chat_Text'] = $message['content']['voice_message_url'];
	}
	
	$m['Chat_Text_censored'] = censor($m['Chat_Text']);
	
	$m['Chat_ID'] = $message['id'];
	$m['Chat_Timestamp'] = date("Y-m-d H:i:s",strtotime($message['meta']['timestamp']));
	$m['Bot_ID'] = $bot_id;
	$m['User_ID'] = $user_id;
	$chat_conversation[$m['Chat_ID']] = $m;
	return $m;
}

function process_reaction($reaction){
	global $chat_conversation;
	
	$reaction = array_merge($reaction,$chat_conversation[$reaction['message_id']]);
	return $reaction;
}

db_close();
