<?php
include 'db_connect.php';
session_start();

// Check if customer_id is set in session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("error" => "Customer not logged in"));
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $customer_id = $_SESSION['user_id'];
    $customer_name = $_SESSION['full_name'];
    $item_id = $data['item_id'];
    $item_name = $data['item_name'];
    $amount = $data['amount'];
    $price = $data['price'];
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
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "error" => "Error updating item in cart"));
        }
    } else {
        // Item does not exist in the cart, insert a new row
        $insert_query = "INSERT INTO order_list (customer_id, customer_name, item_id, item_name, amount, price, total, ordered, status) 
                         VALUES ('$customer_id', '$customer_name', '$item_id', '$item_name', '$amount', '$price', '$total', NOW(), '$status')";

        if ($conn->query($insert_query) === TRUE) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "error" => "Error adding item to cart"));
        }
    }

    $conn->close();
} else {
    echo json_encode(array("success" => false, "error" => "Invalid request method"));
}
?>
