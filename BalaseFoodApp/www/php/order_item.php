<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

include 'db_connect.php';

// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the data from the PUT request
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract data from the request
    $customer_id = $data['user_id'];
    $customer_name = $data['full_name'];
    $item_id = $data['item_id'];
    $item_name = $data['item_name'];
    $amount = $data['amount'];
    $price = $data['price'];
    $total = $amount * $price;
    $status = 'cart';

    // Escape variables for security
    $customer_id = $conn->real_escape_string($customer_id);
    $customer_name = $conn->real_escape_string($customer_name);
    $item_id = $conn->real_escape_string($item_id);
    $item_name = $conn->real_escape_string($item_name);
    $amount = $conn->real_escape_string($amount);
    $price = $conn->real_escape_string($price);
    $total = $conn->real_escape_string($total);

    // Check if the item already exists in the cart for this customer
    $query = "SELECT * FROM order_list WHERE customer_id = '$customer_id' AND item_id = '$item_id' AND status = '$status'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Item exists in the cart, update the row
        $row = $result->fetch_assoc();
        $new_amount = $row['amount'] + $amount;
        $new_total = $new_amount * $price;

        $update_query = "UPDATE order_list SET amount = '$new_amount', total = '$new_total', ordered = NOW() WHERE order_id = " . $row['order_id'];

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
