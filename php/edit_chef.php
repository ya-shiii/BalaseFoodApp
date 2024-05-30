<?php
// Include the database connection
include 'db_connect.php';

// Check if form fields are submitted
if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    // Parse PUT request body as JSON
    $putData = json_decode(file_get_contents("php://input"), true);

    // Sanitize input to prevent SQL injection
    $user_id = mysqli_real_escape_string($conn, $putData['user_id']);
    $full_name = mysqli_real_escape_string($conn, $putData['full_name']);
    $u_name = mysqli_real_escape_string($conn, $putData['username']);
    $password = mysqli_real_escape_string($conn, $putData['password']);
    $email = mysqli_real_escape_string($conn, $putData['email']);
    $phone = mysqli_real_escape_string($conn, $putData['phone']);
    $address = mysqli_real_escape_string($conn, $putData['address']);

    // Query to update chef information
    $query = "UPDATE in_charge SET full_name='$full_name', username='$u_name', password='$password', email='$email', phone='$phone', address='$address' WHERE user_id=$user_id";

    // Execute query
    if (mysqli_query($conn, $query)) {
        // Update successful
        $response = array(
            'success' => true,
            'message' => 'Chef information updated successfully.'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Update failed
        $response = array(
            'success' => false,
            'message' => 'Error updating chef information: ' . mysqli_error($conn)
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Invalid request method
    $response = array(
        'success' => false,
        'message' => 'Invalid request method.'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
