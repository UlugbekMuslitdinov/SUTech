<?php

$servername = "localhost";
$username = "root";
// $password = "SU-mon5";
$password = "Kampoopoo889";
$dbname = "sucs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>


