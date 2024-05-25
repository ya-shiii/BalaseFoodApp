<?php
// Include the database connection
include 'db_connect.php';

// Start session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs to prevent SQL injection
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $u_name = $conn->real_escape_string($_POST['u_name']);
    $password = $conn->real_escape_string($_POST['password']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);

    // Check for duplicate username in customers
    $check_username_query = "SELECT * FROM customers WHERE username='$u_name'";
    $check_username_result = $conn->query($check_username_query);
    if ($check_username_result->num_rows > 0) {
        // Username already exists
        echo '<script>alert("Username already exists.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';

        exit();
    }

    // Check for duplicate username in in_charge
    $check_chef_username_query = "SELECT * FROM in_charge WHERE username='$u_name'";
    $check_chef_username_result = $conn->query($check_chef_username_query);
    if ($check_chef_username_result->num_rows > 0) {
        // Username already exists
        echo '<script>alert("Username already exists.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';

        exit();
    }

    // Check for duplicate email in customers
    $check_email_query = "SELECT * FROM customers WHERE email='$email'";
    $check_email_result = $conn->query($check_email_query);
    if ($check_email_result->num_rows > 0) {
        // Email already exists
        echo '<script>alert("Email already exists.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';

        exit();
    }

    // Check for duplicate email in in_charge
    $check_chef_email_query = "SELECT * FROM in_charge WHERE email='$email'";
    $check_chef_email_result = $conn->query($check_chef_email_query);
    if ($check_chef_email_result->num_rows > 0) {
        // Email already exists
        echo '<script>alert("Email already exists.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';

        exit();
    }

    // Check if the username is "admin"
    if ($u_name === 'admin') {
        // Username cannot be "admin"
        echo '<script>alert("Username cannot be \'admin\'");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';

        exit();
    }


    // Insert the new chef into the database
    $insert_query = "INSERT INTO in_charge (full_name, username, password, email, phone, address) 
                    VALUES ('$full_name', '$u_name', '$password', '$email', '$phone', '$address')";
    if ($conn->query($insert_query) === TRUE) {
        // Chef added successfully
        echo '<script>alert("New chef added successfully.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        exit();
    } else {
        // Error inserting chef
        echo '<script>alert("Error adding new chef.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        exit();
    }
} else {
    // Form not submitted
    echo '<script>alert("Form not submitted.");</script>';
    echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
    
}

// Close database connection
$conn->close();
exit();
?>