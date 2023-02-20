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
<li><a href="downHist.php?dltype=rtf">Download uncensored rtf chatlog</a></li>
<li><a href="downHist.php?dltype=rtf&dlcensor=true">Download censored rtf chatlog</a></li>
<li><a href="downHist.php?dltype=sql">Download uncensored sql chatlog</a></li>
<li><a href="downHist.php?dltype=sql&dlcensor=true">Download censored sql chatlog</a></li>
<li><a href="downHist.php?dltype=csv">Download uncensored csv chatlog</a></li>
<li><a href="downHist.php?dltype=csv&dlcensor=true">Download censored csv chatlog</a></li>
</ul>
<?php
addFooter();