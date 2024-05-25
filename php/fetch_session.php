<?php

// Start session
session_start();

// Initialize response array
$response = array();

// Check if session variables are set
if(isset($_SESSION['username']) && isset($_SESSION['role'])) {
    // Fetch session data
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
    $u_id = $_SESSION['user_id'];

    // Assuming full name is stored in the session, replace 'fullname' with the appropriate session variable
    $fullname = $_SESSION['full_name'];

    // Set success response with session data
    $response['success'] = true;
    $response['user_id'] = $u_id;
    $response['username'] = $username;
    $response['role'] = $role;
    $response['fullname'] = $fullname;
} else {
    // Session variables not set, indicating unauthorized access
    // Logout user
    session_unset();
    session_destroy();

    // Set failed response
    $response['success'] = false;
    $response['message'] = "Session not set, please login.";
}

// Encode response array into JSON and echo
echo json_encode($response);

?>
