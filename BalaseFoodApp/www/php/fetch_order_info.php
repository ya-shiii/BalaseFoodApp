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

// Check if order_id is provided
if (isset($_POST['order_id'])) {
    // Sanitize the input to prevent SQL injection
    $order_id = $_POST['order_id'];

    // Query to fetch Item information
    $query = "SELECT * FROM order_list WHERE order_id = $order_id"; 
    $result = $conn->query($query);

    // Check if the query was successful
    if ($result) {
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            // Fetch the Item information
            $order_info = $result->fetch_assoc();

            // Return the driver information as JSON
            echo json_encode($order_info);
        } else {
            // No matching Item found
            echo json_encode(['error' => 'Item not found']);
        }
    } else {
        // Query execution failed
        echo json_encode(['error' => 'Query failed']);
    }
} else {
    // u_id parameter is missing
    echo json_encode(['error' => 'Missing id parameter']);
}
?>
