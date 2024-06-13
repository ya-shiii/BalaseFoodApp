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

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the data from the PUT request
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract user_id from the request
    $user_id = $data['user_id'];

    // Escape user_id for security (assuming it's an integer)
    $user_id = $conn->real_escape_string($user_id);

    // Update the order_list table
    $query = "UPDATE order_list 
              SET status = 'Pending', ordered = NOW() 
              WHERE customer_id = $user_id AND status = 'Cart'";

    if ($result = $conn->query($query)) {
        if ($conn->affected_rows > 0) {
            // Successfully updated
            echo json_encode(array("success" => true, "message" => "Order checked out successfully!"));
        } else {
            // No rows updated, possibly no items in cart
            echo json_encode(array("success" => false, "error" => "No items in cart to check out"));
        }
    } else {
        // Query execution failed
        echo json_encode(array("success" => false, "error" => "Error: " . $conn->error));
    }
} else {
    echo json_encode(array("success" => false, "error" => "Invalid request method"));
}

// Close the database connection
$conn->close();
?>
