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

// Safely fetch the logged-in user id
$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);


// Fetch orders from the database
$query = "SELECT ol.order_id, ol.customer_id, ol.ordered, ol.status, ol.customer_name, GROUP_CONCAT(CONCAT('<b>', ol.amount, 'x</b> ', ol.item_name) SEPARATOR ', ') AS item_names, SUM(ol.total) AS total FROM order_list ol WHERE customer_id = '$user_id' AND status != 'Cart'";

// Group the rows based on customer_id, ordered, and status
$query .= " GROUP BY ol.customer_id, ol.ordered, ol.status ORDER BY FIELD(status, 'Pending', 'Preparing', 'Serving', 'Completed') ASC";

$result = $conn->query($query);

// Check if there are any orders
if ($result->num_rows > 0) {
    // Loop through the results
    while ($row = $result->fetch_assoc()) {
        $order_id = $row['order_id'];
        $customer_id = $row['customer_id'];
        $ordered_time = $row['ordered'];
        $status = $row['status'];
        $customer_name = $row['customer_name'];
        $item_names = explode(', ', $row['item_names']);
        $total = $row['total'];

        // Create an order array with details
        $order = array(
            "order_id" => $order_id,
            "customer_id" => $customer_id,
            "ordered_time" => $ordered_time,
            "status" => $status,
            "customer_name" => $customer_name,
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
