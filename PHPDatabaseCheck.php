<?php

$AllianceOne = $_GET["ID"];


$servername = "newdatabasetest.mysql.database.azure.com";
$username = "Shade@newdatabasetest";
$password = "Lesbian303";

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
