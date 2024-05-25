<?php

// Include the database connection
include 'db_connect.php';

session_start();

$user_id = $_SESSION['user_id'];
// Initialize an array to store orders
$orders = array();

// Query to fetch orders from in_charge table
$query = "SELECT * FROM order_list WHERE customer_id = $user_id AND `status` = 'Cart'";
$result = $conn->query($query);

// Check if query was successful
if ($result && $result->num_rows > 0) {
    // Fetch orders and add them to the array
    while ($row = $result->fetch_assoc()) {
        
        // Generate the filename string
        $filename = str_replace(' ', '_', $row['item_name']) . '.jpg';


        $order = array(
            'order_id' => $row['order_id'],
            'item_id' => $row['item_id'],
            'name' => $row['item_name'],
            'amount' => $row['amount'],
            'price' => $row['price'],
            'total' => $row['total'],
            'filename' => $filename
        );
        $orders[] = $order;
    }
} else {
    // No orderss found
    echo json_encode(array());
    exit(); // Stop further execution
}

// Close the database connection
$conn->close();

// Encode the array as JSON and echo
echo json_encode($orders);
