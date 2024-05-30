<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, return an error message
    echo json_encode(array("error" => "User not logged in"));
    exit();
}

// Initialize an array to store orders
$orders = array();

// Fetch orders from the database
$query = "SELECT order_id, customer_id, ordered, status, GROUP_CONCAT(item_name SEPARATOR ', ') AS item_names, SUM(total) AS total FROM order_list WHERE customer_id = '{$_SESSION['user_id']}'";

// Group the rows based on the conditions
$query .= " GROUP BY ordered ORDER BY ordered DESC";

$result = $conn->query($query);

// Check if there are any orders
if ($result->num_rows > 0) {
    // Loop through the results
    while ($row = $result->fetch_assoc()) {
        $order_id = $row['order_id'];
        $customer_id = $row['customer_id'];
        $ordered_time = $row['ordered'];
        $status = $row['status'];
        $item_names = explode(', ', $row['item_names']); // Convert to array
        $total = $row['total'];

        // Create an order array with details
        $order = array(
            "order_id" => $order_id,
            "customer_id" => $customer_id,
            "ordered_time" => $ordered_time,
            "status" => $status,
            "item_names" => $item_names,
            "total" => $total
        );

        // Add order to the orders array
        $orders[] = $order;
    }
}

// Close the connection
$conn->close();

// Return orders as JSON
echo json_encode($orders);
?>
