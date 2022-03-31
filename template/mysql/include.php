<?php

//require_once("./template/mysql/link.php");
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . "/template/mysql/link.php");
// require_once("link.php");
function selectDB($DBname) {
	mysql_select_db($DBname) or die(mysql_error());
}

function select_db($DBname) {
	if (!$db_link) {
		$db_link=mysqli_connect('mysql_host', 'sucsweb', 'W3bZ!pp3d', 'sucs');
	}
	$db_link->select_db($DBname);
	
	return $db_link;
}

?>
