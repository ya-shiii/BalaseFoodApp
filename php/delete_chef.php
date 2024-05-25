<?php
// Include your database connection file
include 'db_connect.php';

// Initialize response array
$response = array();

// Check if u_id is set and not empty
if (isset($_POST['u_id']) && !empty($_POST['u_id'])) {
    // Sanitize the input to prevent SQL injection
    $u_id = mysqli_real_escape_string($conn, $_POST['u_id']);

    // Construct the DELETE query
    $delete_query = "DELETE FROM in_charge WHERE user_id = '$u_id'";

    // Execute the query
    if (mysqli_query($conn, $delete_query)) {
        // Query executed successfully
        $response['success'] = true;
        $response['message'] = 'Chef removed successfully.';
    } else {
        // Error executing the query
        $response['success'] = false;
        $response['message'] = 'Error: ' . mysqli_error($conn);
    }
} else {
    // u_id parameter not set or empty
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
