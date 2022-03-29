<?php
	$DBlink=mysqli('localhost', 'root', 'Hockey25jh', 'sucs', 3306);
if ($DBlink->connect_error) {
    die("Connection failure: . $DBlink->connect_error");
}
?>