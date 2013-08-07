<?php
	$host='yourremote';
	$user = 'youruser';
	$pass = 'yourpass';
	$db = 'yourdb';
   
	mysql_connect($host, $user, $pass) 
		or die("Erreur dans la connexion à la base <br />".mysql_errno().':'.mysql_error());
	 
	mysql_select_db($db)
		or die("Erreur dans la selection de la base <br />".mysql_errno().':'.mysql_error());
?>