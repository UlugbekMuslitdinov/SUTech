<?php

$servername = "localhost";
$username = "sucsweb";
// $password = "SU-mon5";
$password = "W3bZ!pp3d";
$dbname = "sucs";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
