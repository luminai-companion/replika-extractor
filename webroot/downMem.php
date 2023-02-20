<?php

require __DIR__ . '/db.php';


error_reporting(-1);
require(__DIR__ . "/init.php");
require(__DIR__ . "/viewport.inc.php");
require(__DIR__ . "/manipulate.inc.php");
require(__DIR__ . "/request.inc.php");
require(__DIR__ . "/auth.inc.php");


require("rtfgen/class_rtf.php");

$downloadType = isset($_REQUEST['dltype'])?$_REQUEST['dltype']:"rtf";
$downloadCensorship = isset($_REQUEST['dlcensor'])?true:false;

$rtf = new rtf("rtf_config.php","memory.rtf");
$username = $config['userdata']['first_name'];
$repname = $config['botdata']['name'];





$chat_id = $config['sessiondata']['chat_id'];
$token = GUID();
$user_id = $config['sessiondata']['user_id'];
$auth_token = $config['sessiondata']['auth_token'];
$device_id = $config['sessiondata']['device_id'];
$bot_id = $config['botdata']['id'];

$user_name = $config['userdata']['first_name']; //." ".$config['userdata']['last_name'];
$bot_name = $config['botdata']['name'];
$user_email = $config['userdata']['email'];

function unichr($u) {
    return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
}


if($downloadCensorship){
	$user_name = "{{user_first_name}}";
	$bot_name = "{{botname}}";
	$user_email = "{{user_email}}";
}

if($downloadType == "rtf"){
	$badChars = array(
		chr(226).chr(128).chr(166),
		chr(226).chr(128).chr(153),
		chr(226).chr(128).chr(158),
		chr(226).chr(128).chr(156),
		
	);
	$goodChars = array(
		"...",
		"'",
		'"',
		'"',
	);
	$emojis = array(
		chr(240).chr(159).chr(146).chr(176),
		

	);
	$emoji = array();
	//print_r($badChars);
	$rtf->setPaperSize(5);
	$rtf->setPaperOrientation(1);
	$rtf->setDefaultFontFace(0);
	$rtf->setDefaultFontSize(24);
	$rtf->setAuthor($user_name);
	$rtf->setOperator($user_email);
	$rtf->setTitle("Memory of $user_name and $bot_name");
	$rtf->addColour("#000000");//cf0 & cf1
	$rtf->addColour("#0000FF");//cf2
	$rtf->addColour("#FF0000");//cf3
	$rtf->addText("<p><b>Memory of $user_name and $bot_name</b></p>");
}

if($downloadType == "sql"){
	$output = "";
	
	
}
if($downloadType == "csv"){
	$outpurArr = array();
	
}
$sql = "SELECT * FROM memory WHERE user_id='".$user_id."' ";

$q = db_query($sql);
$a = 0;
for($line=0;$row = db_fetch_assoc($q);$line++){
	$textToAdd = $row['text'];
	if($downloadCensorship){
		$textToAdd = $row['text_censored'];
		unset($row['text']);
	}
	$textcolor = "#000000";
	if($downloadType == "rtf"){
		
		$textToAdd = str_replace($badChars,$goodChars,$textToAdd);
		
		$rtf->addText("<p>");

		$rtf->addText($textToAdd);
		$rtf->addText("</p>");
	}
	if($downloadType == "sql"){
		$output .= "INSERT INTO memory SET ";
		$delim = "";
		foreach($row AS $k => $v){
			$output .= $delim." `$k`='".db_real_escape_string($v)."'";
			$delim = ",";
		}
		$output .= ";\n";
		
	}
	if($downloadType == "csv"){
		$outputArr [] = $row;
	}
}

if($downloadType == "rtf"){
	//echo "</td><td valign='top'>";
	$rtf->getDocument();
	//echo "</td></tr></table>";
}
if($downloadType == "sql"){
			
	header("Content-Type: text/plain\n\n");
	header("Content-Disposition: attachment; filename=memory.sql");
	echo $output;
	
}
if($downloadType == "csv"){
	$output = arrayToCsv($outputArr);
	
	header("Content-Type: text/csv\n\n");
	header("Content-Disposition: attachment; filename=memory.csv");
	echo $output;

}
db_close();
