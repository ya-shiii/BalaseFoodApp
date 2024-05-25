<?php
// Include the database connection
include 'db_connect.php';

// Check if order_id is provided
if (isset($_POST['order_id'])) {
    // Sanitize the input to prevent SQL injection
    $order_id = $_POST['order_id'];

    // Delete the order
    $query = "DELETE FROM order_list WHERE order_id = $order_id";

    if ($conn->query($query) === TRUE) {
        // Successfully deleted
        echo "Order deleted successfully!";
    } else {
        // Error deleting order
        echo "Error deleting order: " . $conn->error;
    }
} else {
    // If order_id is not provided
    echo "Order ID not provided.";
}

// Close the database connection
$conn->close();
?>
