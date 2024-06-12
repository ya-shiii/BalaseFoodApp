<?php
include 'db_connect.php';
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if customer_id is set in session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "error" => "Customer not logged in"));
    exit();
}

// Get the user ID from session
$customer_id = $_SESSION['user_id'];

// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Update the order_list table
    $query = "UPDATE order_list 
              SET status = 'Pending', ordered = NOW() 
              WHERE customer_id = $customer_id AND status = 'Cart'";

    if ($result = $conn->query($query)) {
        if ($conn->affected_rows > 0) {
            // Successfully updated
            echo json_encode(array("success" => true, "message" => "Order checked out successfully!"));
        } else {
            // No rows updated, possibly no items in cart
            echo json_encode(array("success" => false, "error" => "No items in cart to check out"));
        }
    } else {
        // Query execution failed
        echo json_encode(array("success" => false, "error" => "Error: " . $conn->error));
    }
}

// Close the database connection
$conn->close();
?>
