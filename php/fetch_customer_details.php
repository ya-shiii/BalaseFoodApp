<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "error" => "User not logged in"));
    exit();
}

// Fetch account details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM customers WHERE user_id = '$user_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(
        array(
            "success" => true,
            "user_id" => $row['user_id'],
            "username" => $row['username'],
            "password" => $row['password'],
            "full_name" => $row['full_name'],
            "email" => $row['email'],
            "phone" => $row['phone'],
            "address" => $row['address']
        )
    );
} else {
    echo json_encode(array("success" => false, "error" => "No account details found"));
}

$conn->close();
?>