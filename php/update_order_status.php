<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, return an error message
    echo json_encode(array("error" => "User not logged in"));
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if customerId, orderTime, and newStatus are set in the POST data
    if (isset($_POST['customerId']) && isset($_POST['orderTime']) && isset($_POST['newStatus'])) {
        // Sanitize input data
        $customerId = mysqli_real_escape_string($conn, $_POST['customerId']);
        $orderTime = mysqli_real_escape_string($conn, $_POST['orderTime']);
        $newStatus = mysqli_real_escape_string($conn, $_POST['newStatus']);

        // Update the order status in the database for all orders with the same customer ID and order time
        $query = "UPDATE order_list SET status = '$newStatus' WHERE customer_id = '$customerId' AND ordered = '$orderTime'";
        if ($conn->query($query) === TRUE) {
            // If update successful, return success response
            echo json_encode(array("success" => true));
        } else {
            // If update failed, return error response
            echo json_encode(array("success" => false, "error" => "Failed to update order status"));
        }
    } else {
        // If customerId, orderTime, or newStatus not set in POST data, return error response
        echo json_encode(array("success" => false, "error" => "customerId, orderTime, or newStatus not provided"));
    }
} else {
    // If request method is not POST, return error response
    echo json_encode(array("success" => false, "error" => "Invalid request method"));
}

// Close the connection
$conn->close();
?>
