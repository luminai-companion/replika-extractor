<?php
function GUID(){
	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function getTimestampHash($device_id){
	return md5('time_covfefe_prefix=2020_' . $device_id); // found in my.replika.com/static/js/utils/fetchOptions.ts on line 84
}

function censor($text){
	global $config;
	$arrCensor =  array($config['userdata']['first_name'],$config['userdata']['last_name'],$config['botdata']['name'],);
	$arrReplace = array("{{user_first_name}}","{{user_last_name}}","{{botname}}",);
	if(isset($config['persons'])){
		for($i=0;$i<count($config['persons']);$i++){
			$person = $config['persons'][$i];
			$arrCensor[] = $person['name'];
			$arrReplace[] = "{{".$person['relation']."}}";
		}
	}
	return str_ireplace($arrCensor,$arrReplace,$text);
	
}

function arrayToCsv( array $fields, $includeHeaderline=true, $delimiter = ';', $enclosure = '"', $encloseAll = true, $nullToMysqlNull = false ) {
	$delimiter_esc = preg_quote($delimiter, '/');
	$enclosure_esc = preg_quote($enclosure, '/');
	$outputString = "";
	foreach(array_keys($fields[0]) AS $field){
		if ($field === null && $nullToMysqlNull) {
			$output[] = 'NULL';
			continue;
		}

		// Enclose fields containing $delimiter, $enclosure or whitespace
		if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
			$field = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
		}
		$output[] = $field." ";		
	}
	$outputString .= implode( $delimiter, $output )."\r\n";
	foreach($fields as $tempFields) {
		$output = array();
		foreach ( $tempFields as $field ) {
			if ($field === null && $nullToMysqlNull) {
				$output[] = 'NULL';
				continue;
			}

			// Enclose fields containing $delimiter, $enclosure or whitespace
			if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
				$field = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
			}
			$output[] = $field." ";
		}
		$outputString .= implode( $delimiter, $output )."\r\n";
	}
return $outputString; }