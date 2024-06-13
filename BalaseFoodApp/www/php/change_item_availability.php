<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}


// Start the session
session_start();

// Include your database connection file
include 'db_connect.php';

// Initialize response array
$response = array();

// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get the JSON input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if item_id is set and not empty
    if (isset($data['item_id']) && !empty($data['item_id'])) {
        // Sanitize the input to prevent SQL injection
        $item_id = mysqli_real_escape_string($conn, $data['item_id']);

        // Fetch the current status of the item
        $query = "SELECT status FROM menu_list WHERE item_id = '$item_id'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $current_status = $row['status'];

            // Toggle the status
            if ($current_status === 'Available') {
                $new_status = 'Not Available';
            } else {
                $new_status = 'Available';
            }

            // Update the status in the database
            $update_query = "UPDATE menu_list SET status = '$new_status' WHERE item_id = '$item_id'";
            if (mysqli_query($conn, $update_query)) {
                $response['success'] = true;
                $response['message'] = 'Item status updated successfully.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Error updating item status: ' . mysqli_error($conn);
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Item not found.';
        }
    } else {
        // item_id parameter not set or empty
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
