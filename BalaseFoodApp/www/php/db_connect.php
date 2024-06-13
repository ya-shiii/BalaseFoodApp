<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}



// Database credentials
$servername = "srv1457.hstgr.io"; //srv1457.hstgr.io
$username = "u663034616_balase"; //u663034616_balase
$password = "3JvSRq?N;"; //3JvSRq?N;
$dbname = "u663034616_balasefoodapp"; //u663034616_balasefoodapp

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
