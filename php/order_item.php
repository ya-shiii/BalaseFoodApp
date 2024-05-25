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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $amount = $_POST['amount'];
    $price = $_POST['price'];
    $total = $amount * $price;
    $status = 'cart';

    // Check if the item already exists in the cart for this customer
    $query = "SELECT * FROM order_list WHERE customer_id = '$customer_id' AND item_id = '$item_id' AND status = 'cart'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Item exists in the cart, update the row
        $row = $result->fetch_assoc();
        $new_amount = $row['amount'] + $amount;
        $new_total = $new_amount * $price;

        $update_query = "UPDATE order_list SET amount = '$new_amount', total = '$new_total', ordered = NOW() 
                         WHERE order_id = " . $row['order_id'];

        if ($conn->query($update_query) === TRUE) {
            echo '<script>alert("Item updated in cart successfully.");</script>';
            echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        } else {
            echo '<script>alert("Error updating item in cart.");</script>';
            echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        }
    } else {
        // Item does not exist in the cart, insert a new row
        $insert_query = "INSERT INTO order_list (customer_id, item_id, item_name, amount, price, total, ordered, status) 
                         VALUES ('$customer_id', '$item_id', '$item_name', '$amount', '$price', '$total', NOW(), '$status')";

        if ($conn->query($insert_query) === TRUE) {
            echo '<script>alert("Item added to cart successfully.");</script>';
            echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        } else {
            echo '<script>alert("Error adding item to cart.");</script>';
            echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        }
    }

    $conn->close();
} else {
    echo '<script>alert("Invalid request method.");</script>';
    echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
}
?>
