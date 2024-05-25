<?php

// Database credentials
$servername = "localhost";
$username = "root"; //u663034616_antiola
$password = ""; //5x:KlOgbH3M
$dbname = "balasefoodapp"; //u663034616_ride_hailing

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
