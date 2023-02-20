<?php

function addHeader(){
	?>
<html>
<head>
<title>Rep extractor</title>
</head>
<body>
<?php	
}

function addDebugHeader(){
	addHeader();
	echo "<pre>\n";
	
}

function addFooter(){
	?>
	</body>
	</html>
	<?php
	
}

function addLoginform(){
	?>
	<form action="login.php" method="post">
	<table width="500" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td align="left">Username: </td><td align="left"><input type="text" name="username"></td>
		</tr>
		<tr>
			<td align="left">Password: </td><td align="left"><input type="password" name="password"></td>
		</tr>
		<tr>
			<td align="left" colspan="2"><input type="submit" value="Login"></td>
		</tr>
		<tr>
			<td align="left" colspan="2">Notice: We need your Replika.ai credentials to authenticate you against the service. We're not saving the credentials in any way.</td>
		</tr>
		
	</table>
	
	</form>
	
	<?php
}