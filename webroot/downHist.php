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

$rtf = new rtf("rtf_config.php","chatlog.rtf");
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
function analyzerow($text,$line=0){
	$text_buffer = "";
	for($i = 0; $i < strlen($text); $i++){
		$prevcharacter = "";
		if($i>0){
			$prevcharacter = $text[($i-1)];
		}
		$character = $text[$i];
		//echo "$character = ".chr(ord($character))." ".ord($character)."<br>";
			$escaped = "";
			if(ord($character = 226)){
				$character = "";
			}
			if(ord($character) == "166"){
				$character = "...";
				$escaped = $character;
			}
			if(ord($character) == "153"){
				$character = "...";
				$escaped = $character;
			}
			
			
			if(ord($character) >= 0x00 && ord($character) < 0x20)
				$escaped = "\\'".dechex(ord($character));
			
			if ((ord($character) >= 0x20 && ord($character) < 0x80) || ord($character) == 0x09 || ord($character) == 0x0A)
				$escaped = $character;
			
			if (ord($character) >= 0x80 and ord($character) < 0xFF){
				//echo "Bad character: $character with ".ord($character)." on line $line<br>\n";
				$escaped = "\\'".dechex(ord($character));
			}

			switch(ord($character)) {
				case 0x5C:
				case 0x7B:
				case 0x7D:
					$escaped = "\\".$character;
					break;
			}
			$escaped = $character;
		
		$text_buffer .= $escaped;
		
	}
	return $text_buffer;
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
	$rtf->setTitle("Chatlog of $user_name and $bot_name");
	$rtf->addColour("#000000");//cf0 & cf1
	$rtf->addColour("#0000FF");//cf2
	$rtf->addColour("#FF0000");//cf3
	$rtf->addText("<p><b>Chatlog of $user_name and $bot_name</b></p>");
}

if($downloadType == "sql"){
	$output = "";
	
	
}
if($downloadType == "csv"){
	$outpurArr = array();
	
}
//addDebugHeader();
//echo "<table><tr><td valign='top'>\n";
$sql = "SELECT * FROM chat_history WHERE user_id='".$user_id."' ORDER BY Chat_Timestamp";
$q = db_query($sql);
$a = 0;
for($line=0;$row = db_fetch_assoc($q);$line++){
	$textToAdd = $row['Chat_Text'];
	if($downloadCensorship){
		$textToAdd = $row['Chat_Text_censored'];
	}
	$textcolor = "#000000";
	if($downloadType == "rtf"){
		
		$textToAdd = str_replace($badChars,$goodChars,$textToAdd);
		
		if(strtolower(mb_detect_encoding($row['Chat_Text'])) == "utf-8"){
			//echo $line."<br>"; 
			//$row['Chat_Text'] = analyzerow($row['Chat_Text'],$line);
			//echo "====<br>";
		}
		
		if($row['Chat_From'] == "bot"){
			$usercolor = "3";
			$userfrom = $bot_name;
		}
		else{
			$usercolor = "2";
			$userfrom = $user_name;
		}
		
		$rtf->addText("<p>[".$row['Chat_Timestamp']."] ");

		$rtf->addText("<cf $usercolor><b>".$userfrom.": </b></cf>");
		$rtf->addText($textToAdd);
		$rtf->addText("</p>");
	}
	if($downloadType == "sql"){
		if($downloadCensorship){
			unset($row['Chat_Text']);
			
		}
		$output .= "INSERT INTO chat_history SET ";
		$delim = "";
		foreach($row AS $k => $v){
			$output .= $delim." `$k`='".db_real_escape_string($v)."'";
			$delim = ",";
		}
		$output .= ";\n";
		
	}
	if($downloadType == "csv"){
		if($downloadCensorship){
			unset($row['Chat_Text']);
			
		}
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
	header("Content-Disposition: attachment; filename=chatlog.sql");
	echo $output;
	
}
if($downloadType == "csv"){
	$output = arrayToCsv($outputArr);
	
	header("Content-Type: text/csv\n\n");
	header("Content-Disposition: attachment; filename=chatlog.csv");
	echo $output;

}
db_close();
