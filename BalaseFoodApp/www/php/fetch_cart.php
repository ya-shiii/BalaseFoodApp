<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Include the database connection
include 'db_connect.php';

// Get the user_id from the GET request
$user_id = isset($_GET['user_id']) ? $conn->real_escape_string($_GET['user_id']) : null;

if ($user_id) {
    // Initialize an array to store orders
    $orders = array();

    // Query to fetch orders and corresponding image paths from the order_list and menu_list tables
    $query = "
        SELECT ol.order_id, ol.item_id, ol.item_name, ol.amount, ol.price, ol.total, ml.img_path
        FROM order_list ol
        JOIN menu_list ml ON ol.item_id = ml.item_id AND ol.item_name = ml.name
        WHERE ol.customer_id = $user_id AND ol.status = 'Cart'
        ORDER BY ol.ordered DESC
    ";
    $result = $conn->query($query);

    // Check if query was successful
    if ($result && $result->num_rows > 0) {
        // Fetch orders and add them to the array
        while ($row = $result->fetch_assoc()) {
            $order = array(
                'order_id' => $row['order_id'],
                'item_id' => $row['item_id'],
                'name' => $row['item_name'],
                'amount' => $row['amount'],
                'price' => $row['price'],
                'total' => $row['total'],
                'img_path' => $row['img_path']
            );
            $orders[] = $order;
        }
    } else {
        // No orders found
        echo json_encode(array());
        exit();
    }

    // Close the database connection
    $conn->close();

    // Encode the array as JSON and echo
    echo json_encode($orders);
} else {
    echo json_encode(array('error' => 'User ID not provided'));
    exit();
}
?>
