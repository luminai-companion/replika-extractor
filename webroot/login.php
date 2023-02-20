<?php
require __DIR__ . '/db.php';


error_reporting(-1);
require(__DIR__ . "/init.php");
require(__DIR__ . "/viewport.inc.php");
require(__DIR__ . "/manipulate.inc.php");
require(__DIR__ . "/request.inc.php");
require(__DIR__ . "/auth.inc.php");




$username = isset($_REQUEST['username'])?$_REQUEST['username']:false;
$password = isset($_REQUEST['password'])?$_REQUEST['password']:false;

if(!$username || !$password || $username == "" || $password == ""){
	error_die("username or password not set");
	
}


$device_id = GUID();
$timestamp_hash =  md5('time_covfefe_prefix=2020_' . $device_id);
$sessiondata['device_id'] = $device_id;
$config['sessiondata']['device_id'] = $device_id;
$sessiondata['timestamp_hash'] = $timestamp_hash;
$config['sessiondata']['timestamp_hash'] = $timestamp_hash;
$API_BASE_URL = "https://my.replika.com/api/mobile/1.4";

$endpoint = $API_BASE_URL."/auth/sign_in/actions/get_auth_type";
$fetchOptions = array("id_string"=>$username);

$Header = getRequestHeader(array("Referer: https://my.replika.com/login"));
$cURLConnection = curl_init();

curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $Header);
curl_setopt($cURLConnection, CURLOPT_ENCODING , '');
curl_setopt($cURLConnection, CURLOPT_POST, 1);
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,json_encode($fetchOptions));
$returndata = curl_exec($cURLConnection);
//echo "1. ".$returndata;
$data = json_decode($returndata,true);
if(isset($data['error_message'])){
	echo "<pre>\n";
	echo $endpoint."\n";
	print_r($Header);
	print_r($fetchOptions);
	print_r($data);
}

if($data['auth_type'] == "password" AND $data["id_type"] =="email"){
	
	
	$endpoint = $API_BASE_URL."/auth/sign_in/actions/auth_by_password";
	$fetchOptions = array(
	  "id_type"=> "email",
	  "id_string"=> $username,
	  "password"=> $password,
	  "capabilities"=> array(
	    "new_mood_titles",
	    "widget.multiselect",
	    "widget.scale",
	    "widget.titled_text_field",
	    "widget.new_onboarding",
	    "widget.app_navigation",
	    "message.achievement",
	    "widget.mission_recommendation",
	    "journey2.daily_mission_activity",
	    "journey2.replika_phrases",
	    "new_payment_subscriptions",
	    "navigation.relationship_settings",
	    "avatar",
	    "diaries.images",
	    "save_chat_items",
	    "wallet",
	    "store.dialog_items",
	    "subscription_popup",
	    "chat_suggestions",
	    "sale_screen",
	    "3d_customization",
	    "3d_customization_v2",
	    "3d_customization_v3",
	    "store_customization",
	    "blurred_messages",
	    "item_daily_reward",
	    "romantic_photos"
	  ),
	  "unity_bundle_version"=> 171
	);
	$Header = getRequestHeader(array("Referer: https://my.replika.com/login/input-password"));	
	$cURLConnection = curl_init();
	
	curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
	curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $Header);
	curl_setopt($cURLConnection, CURLOPT_ENCODING , '');
	curl_setopt($cURLConnection, CURLOPT_POST, 1);
	curl_setopt($cURLConnection, CURLOPT_POSTFIELDS,json_encode($fetchOptions));
	$returndata = curl_exec($cURLConnection);
	//echo "2. ".$returndata;
	$data = json_decode($returndata,true);
	if(isset($data['user_id']) && isset($data['auth_token'])){
		//Login success
		$user_id = $data['user_id'];
		$auth_token = $data['auth_token'];
		
		$sessiondata['user_id'] = $user_id;
		$sessiondata['auth_token'] = $auth_token;
		
		//Get bot data
		$endpoint = $API_BASE_URL."/personal_bot";
		$cURLConnection = curl_init();
		$Header = getRequestHeader(array(
			"Referer: https://my.replika.com/login/input-password",
			"x-auth-token: ".$auth_token,
			"x-user-id: ".$user_id,
		)	);

		curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $Header);
		curl_setopt($cURLConnection, CURLOPT_ENCODING , '');
		$returndata = curl_exec($cURLConnection);
		//echo "3. ".$returndata;
		$data = json_decode($returndata,true);
	//	print_r($Header);
		//print_r($data);
		$bot['id'] = $data['id'];
		$sessiondata['bot_id'] = $data['id'];
		$bot['name'] = $data['name'];
		$bot['gender'] = $data['gender'];
		$bot['xp'] = $data['stats']['score'];
		$bot['age_days'] = $data['stats']['day_counter'];
		$bot['level'] = $data['stats']['current_level']['level_index'];
		
		//get chat data
		$endpoint = $API_BASE_URL."/personal_bot_chat";
		$cURLConnection = curl_init();
		$Header = getRequestHeader(array(
			"Referer: https://my.replika.com/login/input-password",
			"x-auth-token: ".$auth_token,
			"x-user-id: ".$user_id,
			)
		);	

		curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $Header);
		curl_setopt($cURLConnection, CURLOPT_ENCODING , '');
		$returndata = curl_exec($cURLConnection);
		//echo "4. ".$returndata;
		$data = json_decode($returndata,true);
		//print_r($Header);
		//print_r($data);
		$bot['chat_id'] = $data['id'];
		$sessiondata['chat_id'] = $data['id'];
		//user_id	bot_id	name	gender	xp	age_days	level	chat_id	updated_last


		
		
		//Get user profile
		$endpoint = $API_BASE_URL."/profile";
		$cURLConnection = curl_init();
		$Header = getRequestHeader(array(
			"Referer: https://my.replika.com/login/input-password",
			"x-auth-token: ".$auth_token,
			"x-user-id: ".$user_id,
			)
		);	

		curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $Header);
		curl_setopt($cURLConnection, CURLOPT_ENCODING , '');
		$returndata = curl_exec($cURLConnection);
		//echo "5. ".$returndata;
		$data = json_decode($returndata,true);
		//print_r($Header);
		//print_r($data);
		$your['id'] = $data['id'];
		$your['first_name'] = $data['first_name'];
		$your['last_name'] = $data['last_name'];
		$your['onboarding_state'] = $data['onboarding_state'];
		$your['email'] = $data['email_settings']['email'];
		$your['pronoun'] = $data['pronoun'];
		$your['relationship_status'] = $data['relationship_status'];
		//user_id	first_name	last_name	email	pronoun	relationship_status
		//print_r($your);
		//print_r($bot);
		$sql = "REPLACE INTO userdata SET user_id='".$your['id']."', first_name='".$your['first_name']."', last_name='".$your['last_name']."', email='".$your['email']."', pronoun='".$your['pronoun']."', relationship_status='".$your['relationship_status']."'";
		db_query($sql);
		$sql = "REPLACE INTO botdata SET user_id='".$your['id']."', bot_id='".$bot['id']."', name='".$bot['name']."', gender='".$bot['gender']."', xp='".$bot['xp']."', age_days='".$bot['age_days']."', level='".$bot['level']."', chat_id='".$bot['chat_id']."'";
		db_query($sql);		
		setcookie("replikaSession",json_encode($sessiondata),time()+(3600*24*365));
		setcookie("replikaBotdata",json_encode($bot),time()+(3600*24*365));
		setcookie("replikaYourdata",json_encode($your),time()+(3600*24*365));
		$_COOKIE["replikaSession"] =json_encode($sessiondata);
		$_COOKIE["replikaBotdata"] =json_encode($bot);
		$_COOKIE["replikaYourdata"] =json_encode($your);
		loadConfig();
		header("Location: index.php");
		exit();

		
		
		
	}
}
