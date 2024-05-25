<?php

// Include the database connection
include 'db_connect.php';

// Initialize an array to store users
$users = array();

// Query to fetch users from user_list table
$query = "SELECT *, (SELECT COUNT(*) FROM order_list WHERE customer_id = user_id) AS num_orders FROM customers";
$result = $conn->query($query);

// Check if query was successful
if ($result && $result->num_rows > 0) {
    // Fetch users and add them to the array
    while ($row = $result->fetch_assoc()) {
        $user = array(
            'user_id' => $row['user_id'],
            'full_name' => $row['full_name'],
            'address' => $row['address'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'num_orders' => $row['num_orders']
        );
        $users[] = $user;
    }
} else {
    // No users found
    echo json_encode(array());
    exit(); // Stop further execution
}

// Close the database connection
$conn->close();

// Encode the array as JSON and echo
echo json_encode($users);
