<?php
// Include the database connection
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = intval($_POST['order_id']);
    $itemId = intval($_POST['item_id']);
    $customerId = intval($_POST['customer_id']);
    $amount = intval($_POST['amount']);
    $price = floatval($_POST['price']);
    $total = $amount * $price;

    // Update the amount in the database
    $query = "UPDATE order_list SET amount = $amount, total = $total WHERE order_id = $orderId";

    if ($conn->query($query) === TRUE) {
        echo '<script>alert("Order updated successfully.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
    } else {
        echo '<script>alert("Error updating order.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
    }

    $conn->close();
} else {
    echo '<script>alert("Invalid request method.");</script>';
    echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
}
?>
