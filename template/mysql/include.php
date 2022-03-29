<?php

//require_once("./template/mysql/link.php");
//define('__ROOT__', dirname(dirname(__FILE__)));
require_once("C:/xampp/htdocs/template/mysql/link.php");
// require_once("link.php");
function selectDB($DBname) {
	$conn = mysqli($DBname);
	if ($conn->connect_error) {
		die("Connection failure: . $conn->connect_error");
	}
}

function select_db($DBname) {
	$db_link =mysqli('localhost', 'root', 'Hockey25jh', 'sucs', 3306);
	if (!$db_link) {
		$db_link=mysqli_connect('localhost', 'root', 'Hockey25jh', 'sucs', 3306);
	}
	$db_link->select_db($DBname);
	
	return $db_link;
}

?>
