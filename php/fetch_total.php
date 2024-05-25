<?php
// Include the database connection
include 'db_connect.php';

// Start the session
session_start();

// Check if customer_id is set in session
if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("Customer not logged in.");</script>';
    echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
    exit();
}

$customer_id = $_SESSION['user_id'];
// Initialize total amount
$totalAmount = 0;

// Query to fetch the total amount from the order_list table
$query = "SELECT SUM(total) AS total_amount FROM order_list WHERE customer_id = '$customer_id' AND status = 'cart'";
$result = $conn->query($query);

// Check if the query was successful
if ($result) {
    $row = $result->fetch_assoc();
    $totalAmount = $row['total_amount'];
}

// Return the total amount as JSON
echo json_encode(['total_amount' => $totalAmount]);

// Close the database connection
$conn->close();
?>