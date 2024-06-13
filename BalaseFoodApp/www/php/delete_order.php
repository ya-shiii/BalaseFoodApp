<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}


include 'db_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Read the input from the DELETE request
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // Check if order_id is provided
        if (isset($data['order_id'])) {
            // Sanitize the input to prevent SQL injection
            $order_id = intval($data['order_id']); // Use intval to ensure it's an integer

            // Delete the order
            $query = "DELETE FROM order_list WHERE order_id = $order_id";

            if ($conn->query($query) === TRUE) {
                // Successfully deleted
                echo json_encode(array("success" => true, "message" => "Order deleted successfully!"));
            } else {
                // Error deleting order
                echo json_encode(array("success" => false, "error" => "Error deleting order: " . $conn->error));
            }
        } else {
            // If order_id is not provided
            echo json_encode(array("success" => false, "error" => "Order ID not provided."));
        }
    } else {
        // Invalid JSON data
        echo json_encode(array("success" => false, "error" => "Invalid JSON data."));
    }
} else {
    // Invalid request method
    echo json_encode(array("success" => false, "error" => "Invalid request method."));
}

// Close the database connection
$conn->close();
?>
