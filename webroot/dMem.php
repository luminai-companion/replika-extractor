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
<li><a href="downMem.php?dltype=rtf">Download uncensored rtf memory</a></li>
<li><a href="downMem.php?dltype=rtf&dlcensor=true">Download censored rtf memory</a></li>
<li><a href="downMem.php?dltype=sql">Download uncensored sql memory</a></li>
<li><a href="downMem.php?dltype=sql&dlcensor=true">Download censored sql memory</a></li>
<li><a href="downMem.php?dltype=csv">Download uncensored csv memory</a></li>
<li><a href="downMem.php?dltype=csv&dlcensor=true">Download censored csv memory</a></li>
</ul>
<?php
addFooter();