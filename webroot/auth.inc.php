<?php
/*
		setcookie("replikaSession",json_encode($sessiondata),time()+(3600*24*365));
		setcookie("replikaBotdata",json_encode($bot),time()+(3600*24*365));
		setcookie("replikaYourdata",json_encode($your),time()+(3600*24*365));
*/

function isAdmin(){
	global $config;
	if($config['userdata']['email'] == "sven.sudau.fischer@gmail.com"){
		return true;
	}
	return false;
	
}
function showProfile(){
	global $config;
	$sessiondata = $config['sessiondata'];
	$your = $config['userdata'];
	$bot = $config['botdata'];
	//print_r($config);
	if($config['loggedin']){
			?><table width="500" cellspacing="0" cellpadding="1" style="border: black thin solid">
		<tr>
			<td align="left" colspan="2"><b>Sessiondata</b></td>
		</tr>
		<?php
		while(list($k,$v) = each($sessiondata)){
			?><tr>
			<td align="left"><?php echo $k;?></td><td align="left"><?php echo $v;?></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td align="left" colspan="2"><b>Botdata</b></td>
		</tr>
		<?php

		while(list($k,$v) = each($bot)){
			?><tr>
			<td align="left"><?php echo $k;?></td><td align="left"><?php echo $v;?></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td align="left" colspan="2"><b>Userdata</b></td>
		</tr>
		<?php
		while(list($k,$v) = each($your)){
			?><tr>
			<td align="left"><?php echo $k;?></td><td align="left"><?php echo $v;?></td>
			</tr>
			<?php
		}
		?></table><?php	
	}
	else{
		notLoggedIn();
	}
}

function notLoggedIn(){
	echo "You are not logged in. Please Login <a href='index.php'>here</a>\n";
}

function showNav(){
	?>
<p>Get chat history: <a href="getHist.php">Click here</a></p>
<p>Download history: <a href="dHist.php">Click here</a></p>
<p>Get memory: <a href="getMem.php">Click here</a></p>
<p>Download memory: <a href="dMem.php">Click here</a></p>
<p>Get diary: <a href="getDia.php">Click here</a></p>
<p>Download diary: <a href="dDia.php">Click here</a></p>
<p>Get character models: <a href="getChar.php">Click here</a></p>
<p>Get store models: <a href="getStore.php">Click here</a></p>
<?php if (isAdmin()){ ?>
<p>Get room models: <a href="getRoom.php">Click here</a></p>
<?php } ?>
<p>Logout: <a href="logout.php">Click here</a></p>
<?php
}

function loadConfig(){
	global $config;
	$config['sessiondata'] = isset($_COOKIE['replikaSession'])?json_decode($_COOKIE['replikaSession'],true):false;
	$config['botdata'] = isset($_COOKIE['replikaBotdata'])?json_decode($_COOKIE['replikaBotdata'],true):false;
	$config['userdata'] = isset($_COOKIE['replikaYourdata'])?json_decode($_COOKIE['replikaYourdata'],true):false;
	$config['userpersons'] = isset($_COOKIE['replikaYourpersons'])?json_decode($_COOKIE['replikaYourpersons'],true):false;
	$config['loggedin'] = false;
	
	if($config['sessiondata'] && $config['botdata'] && $config['userdata']){
		$config['loggedin'] = true;
	}
}




loadConfig();


$config['script'] = $_SERVER['PHP_SELF'];
if(!in_array($config['script'],$allowedLogoutScripts) && !$config['loggedin']){
	header("Location: /replika/index.php");
	exit();
}
