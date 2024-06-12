<?php
// Include your database connection file
include 'db_connect.php';

// Initialize response array
$response = array();

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get the JSON input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if user_id is set and not empty
    if (isset($data['user_id']) && !empty($data['user_id'])) {
        // Sanitize the input to prevent SQL injection
        $user_id = mysqli_real_escape_string($conn, $data['user_id']);

        // Construct the DELETE query
        $delete_query = "DELETE FROM customers WHERE user_id = '$user_id'";

        // Execute the query
        if (mysqli_query($conn, $delete_query)) {
            // Query executed successfully
            $response['success'] = true;
            $response['message'] = 'Customer account removed successfully.';
        } else {
            // Error executing the query
            $response['success'] = false;
            $response['message'] = 'Error: ' . mysqli_error($conn);
        }
    } else {
        // user_id parameter not set or empty
        $response['success'] = false;
        $response['message'] = 'Invalid request.';
    }
} else {
    // Invalid request method
    $response['success'] = false;
    $response['message'] = 'Invalid request method.';
}

// Close the database connection
mysqli_close($conn);

// Set the content type to JSON
header('Content-Type: application/json');

// Encode response array into JSON and echo
echo json_encode($response);
exit();
?>
