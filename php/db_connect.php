<?php

// Database credentials
$servername = "localhost";
$username = "root"; //u663034616_balase
$password = ""; //j9&NyKQ&s
$dbname = "balasefoodapp"; //u663034616_balasefoodapp

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
