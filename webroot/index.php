<?php

require __DIR__ . '/db.php';


error_reporting(-1);
require(__DIR__ . "/init.php");
require(__DIR__ . "/viewport.inc.php");
require(__DIR__ . "/manipulate.inc.php");
require(__DIR__ . "/request.inc.php");
require(__DIR__ . "/auth.inc.php");

addHeader();

if(!$config['loggedin']){
	addLoginform();
}
else{
	showProfile();
	showNav();
}
addFooter();