<?php

$AllianceOne = $_GET["ID"];


$servername = "*****";
$username = "*****";
$password = "*****";

$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$databaseTry1 = $conn->select_db($AllianceOne);
if (!$databaseTry1) {
    echo "0";
}
else {
    echo "1";
}


?>
