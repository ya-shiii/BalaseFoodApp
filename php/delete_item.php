<?php
// Include your database connection file
include 'db_connect.php';

// Initialize response array
$response = array();

// Check if item_id is set and not empty
if (isset($_POST['item_id']) && !empty($_POST['item_id'])) {
    // Sanitize the input to prevent SQL injection
    $item_id = mysqli_real_escape_string($conn, $_POST['item_id']);

    // Construct the DELETE query
    $delete_query = "DELETE FROM menu_list WHERE item_id = '$item_id'";

    // Execute the query
    if (mysqli_query($conn, $delete_query)) {
        // Query executed successfully
        $response['success'] = true;
        $response['message'] = 'Menu item removed successfully.';
    } else {
        // Error executing the query
        $response['success'] = false;
        $response['message'] = 'Error: ' . mysqli_error($conn);
    }
} else {
    // item_id parameter not set or empty
    $response['success'] = false;
    $response['message'] = 'Invalid request.';
}

// Close the database connection
mysqli_close($conn);

// Set the content type to JSON
header('Content-Type: application/json');

// Encode response array into JSON and echo
echo json_encode($response);
exit();
?>
