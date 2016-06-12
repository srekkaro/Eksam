<?php
	require_once('function.php');

	session_start();
	connect_db();
	$leht="avaleht";
	if (isset($_GET['leht']) && $_GET['leht']!=""){
		$leht=htmlspecialchars($_GET['leht']);
	}
	include_once('head.html');
	switch($leht){
		case "laadipilt":
			laadipilt();
		break;
		case "alustaSessioon":
			alustaSessioon();
		break;
		case "lopetaSessioon":
			lopetaSessioon();
		break;
		default:
			include_once('avaleht.html');
		break;
		}
	include_once ('foot.html');
?>