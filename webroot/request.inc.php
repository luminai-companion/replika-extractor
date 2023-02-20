<?php

function error_die($msg){
	addHeader();
	echo "<p>".$msg."</p>\n";
	echo "<p><a href='index.php'>Back to Home</a></p>\n";
	addFooter();
	exit();
}
function warning($msg){
	//addHeader();
	echo "<p>".$msg."</p>\n";
	//addFooter();
	//exit();
}
function getRequestHeader($additional=array()){
	global $timestamp_hash,$config;
	//print_r($config);
	if(!isset($config['sessiondata']['device_id'])){
		//echo "Device ID wasn't set. Creating at Request Time\n";
		$config['sessiondata']['device_id'] = GUID();
	}
	$device_id = $config['sessiondata']['device_id'];
	if(!$timestamp_hash){
		$timestamp_hash = getTimestampHash($device_id);
	}
	if(!is_array($additional)){
		if(is_string($additional)){
			$additional = array($additional);
		}
		else{
			$additional = array();
		}
	}
	$authHeader = array();
	if(isset($config['sessiondata']['auth_token']) && isset($config['sessiondata']['user_id'])){
		$authHeader = array(
			"x-auth-token: ".$config['sessiondata']['auth_token'],
			"x-user-id: ".$config['sessiondata']['user_id'],
		);
	}
	$Header = array(
		"accept: application/json",
		"Accept-Encoding: gzip, deflate, br",
		"Accept-Language: de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7",
		"Connection: keep-alive",
		"content-type: application/json",
		"Host: my.replika.com",
		"Origin: https://my.replika.com/",
		"sec-ch-ua: \"Not_A Brand\";v=\"99\", \"Google Chrome\";v=\"109\", \"Chromium\";v=\"109\"",
		"sec-ch-ua-mobile: ?0",
		"sec-ch-ua-platform: \"Windows\"",
		"Sec-Fetch-Dest: empty",
		"Sec-Fetch-Mode: cors",
		"Sec-Fetch-Site: same-origin",
		"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36",
		"x-device-id: ".$device_id,
		"x-device-type: web",
		"x-timestamp-hash: ".$timestamp_hash,
	);
	$Header = array_merge($Header,$authHeader);
	$Header = array_merge($Header,$additional);
	return $Header;
	
}
