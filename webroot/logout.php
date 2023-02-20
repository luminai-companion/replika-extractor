<?php

		setcookie("replikaSession","",time()-10);
		setcookie("replikaBotdata","",time()-10);
		setcookie("replikaYourdata","",time()-10);
		setcookie("replikaYourpersons","",time()-10);
		header("Location: index.php");
		exit();