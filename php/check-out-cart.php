<?php
// Include the database connection
include 'db_connect.php';
session_start();

// Check if customer_id is set in session
if (!isset($_SESSION['user_id'])) {
    echo '<script>alert("Customer not logged in.");</script>';
    echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
    exit();
}

// Get the user ID from session
$customer_id = $_SESSION['user_id'];

// Update the order_list table
$query = "UPDATE order_list 
          SET status = 'Pending', ordered = NOW() 
          WHERE customer_id = $customer_id AND status = 'Cart'";

if ($result = $conn->query($query)) {
    if ($conn->affected_rows > 0) {
        // Successfully updated
        echo '<script>alert("Order checked out successfully!");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
    } else {
        // No rows updated, possibly no items in cart
        echo '<script>alert("No items in cart to check out.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        
    }
} else {
    // Query execution failed
    echo "Error: " . $conn->error;
}

// Close the database connection
$conn->close();
?>