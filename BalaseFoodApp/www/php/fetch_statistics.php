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

// Initialize response array
$response = array();

// Query to count registered customers
$registered_customers_query = "SELECT COUNT(*) AS registered_customers FROM customers";
$registered_customers_result = $conn->query($registered_customers_query);
$registered_customers_row = $registered_customers_result->fetch_assoc();
$response['registeredcustomers'] = $registered_customers_row['registered_customers'];

// Query to count chefs
$incharge_query = "SELECT COUNT(*) AS chefs FROM in_charge";
$incharge_result = $conn->query($incharge_query);
$incharge_row = $incharge_result->fetch_assoc();
$response['chefs'] = $incharge_row['chefs'];

// Query to count order
$order_query = "SELECT COUNT(*) AS orders FROM order_list";
$order_result = $conn->query($order_query);
$order_row = $order_result->fetch_assoc();
$response['orders'] = $order_row['orders'];

// Query to count order
$menu_items_query = "SELECT COUNT(*) AS menu_items FROM menu_list";
$menu_items_result = $conn->query($menu_items_query);
$menu_items_row = $menu_items_result->fetch_assoc();
$response['menu_items'] = $menu_items_row['menu_items'];

// Close database connection
$conn->close();

// Encode response array into JSON and echo
echo json_encode($response);

?>
