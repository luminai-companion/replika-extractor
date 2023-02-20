<?php

$db = false;
function db_connect($dbHost,$dbUser,$dbPass){
	global $db;
	$db = mysqli_connect($dbHost,$dbUser,$dbPass);
	if($db){
		mysqli_set_charset($db, "utf8mb4");
		return $db;
	}
	else{
		db_error_msg("Error while connecting to $dbHost with $dbUser",__FILE__,__LINE__);
	}
}
function db_close(){
	global $db;
	if(!mysqli_close($db)){
		db_error_msg("Error while closing database connection",__FILE__,__LINE__);
	}
}
function db_select_db($dbName){
	global $db;
	if(!mysqli_select_db($db,$dbName)){
		db_error_msg("Error while selecting database $dbName",__FILE__,__LINE__);
	}
}

function db_error_msg($msg,$file=__FILE__,$line=__LINE__){
	global $db;
	echo $msg. " in File: ".$file." Line: ".$line."<br>";
}
function db_query($sql,$file=__FILE__,$line=__LINE__){
	global $db;
	if($q = mysqli_query($db,$sql)){
		return $q;
	}
	else{
		echo $sql;
		echo mysqli_error($db);
		db_error($sql,$file,$line);
		return false;
	}
}
function db_fetch_assoc($q,$file=__FILE__,$line=__LINE__){
	global $db;
	if($row = mysqli_fetch_assoc($q)){
		return $row;
	}
	else{
		db_error("",$file,$line);
		return false;
	}
}
function db_fetch_row($q){
	global $db;
	$row = mysqli_fetch_row($q);
	return $row;
}
function db_fetch_array($q){
	global $db;
	$row = mysqli_fetch_array($q);
	return $row;
}
function db_num_rows($q){
	global $db;
	$row = mysqli_num_rows($q);
	return $row;
}
function db_insert_id(){
	global $db;
	$row = mysqli_insert_id($db);
	return $row;
}
function db_error($sql="",$file=false,$line=false){
	global $db;
	$row = $sql.mysqli_error($db);
	if($file){
		$row .= "<br />In file: ".$file;
	}
	if($line){
		$row .= "<br />In line: ".$line;
	}
	if($row) $row .= "<br />\n";
	return $row;
}
function db_errno(){
	global $db;
	$row = mysqli_errno($db);
	return $row;
}
function db_real_escape_string($string){
	global $db;
	$string = mysqli_real_escape_string($db,$string);
	return $string;
}
function db_real_escape_array($array){
	global $db;
	array_walk($array, function(&$string) use ($db) { 
		$string = db_real_escape_string($string);
	});
	return $array;
}


$dbserv = "localhost";
$dbname = "replika";
$dbuser = "USER";
$dbpass = "PASSWORD";

db_connect($dbserv,$dbuser,$dbpass);
db_select_db($dbname);