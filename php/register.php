<?php

// Include your database connection file
include 'db_connect.php';

// Check if form fields are set and not empty
if (
    isset($_POST['u_name']) && !empty($_POST['u_name']) &&
    isset($_POST['password']) && !empty($_POST['password']) &&
    isset($_POST['full_name']) && !empty($_POST['full_name']) &&
    isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['phone']) && !empty($_POST['phone']) &&
    isset($_POST['address']) && !empty($_POST['address'])
) {
    // Sanitize inputs to prevent SQL injection
    $u_name = mysqli_real_escape_string($conn, $_POST['u_name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if username is "admin"
    if ($u_name === 'admin') {
        echo '<script>alert("Username \"admin\" is not allowed.");</script>';
    } else {
        // Check for duplicate username in customers table
        $check_username_query = "SELECT * FROM customers WHERE username = '$u_name'";
        $check_username_result = mysqli_query($conn, $check_username_query);
        if (mysqli_num_rows($check_username_result) > 0) {
            // Username already exists in customers table
            echo '<script>alert("Username already exists.");</script>';
        } else {
            // Check for duplicate username in in_charge table
            $check_incharge_query = "SELECT * FROM in_charge WHERE username = '$u_name'";
            $check_incharge_result = mysqli_query($conn, $check_incharge_query);
            if (mysqli_num_rows($check_incharge_result) > 0) {
                // Username already exists in in_charge table
                echo '<script>alert("Username already exists.");</script>';
            } else {
                // Construct the INSERT query
                $insert_query = "INSERT INTO customers (username, password, full_name, email, phone, address) VALUES ('$u_name', '$password', '$full_name', '$email', '$phone', '$address')";

                // Execute the query
                if (mysqli_query($conn, $insert_query)) {
                    // Query executed successfully
                    echo '<script>alert("Registration successful.");</script>';
                    
                    echo "<script>window.location.href='../index.html'</script>";
                } else {
                    // Error executing the query
                    echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
                }
            }
        }
    }
} else {
    // Required fields not provided
    echo '<script>alert("All fields are required.");</script>';
}

// Close the database connection
mysqli_close($conn);

// Redirect to admin dashboard
echo "<script>window.history.back()</script>";
exit();
?>
