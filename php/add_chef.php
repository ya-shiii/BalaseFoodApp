<?php
// Include the database connection
include 'db_connect.php';

// Start session
session_start();

// Check if form is submitted via PUT method
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the raw input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Sanitize inputs to prevent SQL injection
    $full_name = $conn->real_escape_string($data['full_name']);
    $u_name = $conn->real_escape_string($data['u_name']);
    $password = $conn->real_escape_string($data['password']);
    $email = $conn->real_escape_string($data['email']);
    $phone = $conn->real_escape_string($data['phone']);
    $address = $conn->real_escape_string($data['address']);

    // Check for duplicate username in customers
    $check_username_query = "SELECT * FROM customers WHERE username='$u_name'";
    $check_username_result = $conn->query($check_username_query);
    if ($check_username_result->num_rows > 0) {
        // Username already exists
        echo json_encode(['success' => false, 'message' => 'Username already exists in customers.']);
        exit();
    }

    // Check for duplicate username in in_charge
    $check_chef_username_query = "SELECT * FROM in_charge WHERE username='$u_name'";
    $check_chef_username_result = $conn->query($check_chef_username_query);
    if ($check_chef_username_result->num_rows > 0) {
        // Username already exists
        echo json_encode(['success' => false, 'message' => 'Username already exists in in_charge.']);
        exit();
    }

    // Check for duplicate email in customers
    $check_email_query = "SELECT * FROM customers WHERE email='$email'";
    $check_email_result = $conn->query($check_email_query);
    if ($check_email_result->num_rows > 0) {
        // Email already exists
        echo json_encode(['success' => false, 'message' => 'Email already exists in customers.']);
        exit();
    }

    // Check for duplicate email in in_charge
    $check_chef_email_query = "SELECT * FROM in_charge WHERE email='$email'";
    $check_chef_email_result = $conn->query($check_chef_email_query);
    if ($check_chef_email_result->num_rows > 0) {
        // Email already exists
        echo json_encode(['success' => false, 'message' => 'Email already exists in in_charge.']);
        exit();
    }

    // Check if the username is "admin"
    if ($u_name === 'admin') {
        // Username cannot be "admin"
        echo json_encode(['success' => false, 'message' => 'Username cannot be "admin".']);
        exit();
    }

    // Insert the new chef into the database
    $insert_query = "INSERT INTO in_charge (full_name, username, password, email, phone, address) 
                    VALUES ('$full_name', '$u_name', '$password', '$email', '$phone', '$address')";
    if ($conn->query($insert_query) === TRUE) {
        // Chef added successfully
        echo json_encode(['success' => true, 'message' => 'New chef added successfully.']);
    } else {
        // Error inserting chef
        echo json_encode(['success' => false, 'message' => 'Error adding new chef.']);
    }
} else {
    // Request method is not PUT
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Close database connection
$conn->close();
?>
