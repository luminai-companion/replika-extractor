<?php


require __DIR__ . '/db.php';


error_reporting(-1);
require(__DIR__ . "/init.php");
require(__DIR__ . "/viewport.inc.php");
require(__DIR__ . "/manipulate.inc.php");
require(__DIR__ . "/request.inc.php");
require(__DIR__ . "/auth.inc.php");


addHeader();
?>
<ul>
<li><a href="downDia.php?dltype=rtf">Download uncensored rtf diary</a></li>
<li><a href="downDia.php?dltype=rtf&dlcensor=true">Download censored rtf diary</a></li>
<li><a href="downDia.php?dltype=sql">Download uncensored sql diary</a></li>
<li><a href="downDia.php?dltype=sql&dlcensor=true">Download censored sql diary</a></li>
<li><a href="downDia.php?dltype=csv">Download uncensored csv diary</a></li>
<li><a href="downDia.php?dltype=csv&dlcensor=true">Download censored csv diary</a></li>
</ul>
<?php
addFooter();