<?php
include 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get the JSON input data
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // Check if all required fields are set and not empty
        if (
            isset($data['u_name']) && !empty($data['u_name']) &&
            isset($data['password']) && !empty($data['password']) &&
            isset($data['full_name']) && !empty($data['full_name']) &&
            isset($data['email']) && !empty($data['email']) &&
            isset($data['phone']) && !empty($data['phone']) &&
            isset($data['address']) && !empty($data['address'])
        ) {
            // Sanitize inputs to prevent SQL injection
            $u_name = mysqli_real_escape_string($conn, $data['u_name']);
            $password = mysqli_real_escape_string($conn, $data['password']);
            $full_name = mysqli_real_escape_string($conn, $data['full_name']);
            $email = mysqli_real_escape_string($conn, $data['email']);
            $phone = mysqli_real_escape_string($conn, $data['phone']);
            $address = mysqli_real_escape_string($conn, $data['address']);

            // Check if username is "admin"
            if ($u_name === 'admin') {
                echo json_encode(array("success" => false, "error" => 'Username "admin" is not allowed.'));
            } else {
                // Check for duplicate username in customers table
                $check_username_query = "SELECT * FROM customers WHERE username = '$u_name'";
                $check_username_result = mysqli_query($conn, $check_username_query);
                if (mysqli_num_rows($check_username_result) > 0) {
                    // Username already exists in customers table
                    echo json_encode(array("success" => false, "error" => 'Username already exists.'));
                } else {
                    // Check for duplicate username in in_charge table
                    $check_incharge_query = "SELECT * FROM in_charge WHERE username = '$u_name'";
                    $check_incharge_result = mysqli_query($conn, $check_incharge_query);
                    if (mysqli_num_rows($check_incharge_result) > 0) {
                        // Username already exists in in_charge table
                        echo json_encode(array("success" => false, "error" => 'Username already exists.'));
                    } else {
                        // Construct the INSERT query
                        $insert_query = "INSERT INTO customers (username, password, full_name, email, phone, address) VALUES ('$u_name', '$password', '$full_name', '$email', '$phone', '$address')";

                        // Execute the query
                        if (mysqli_query($conn, $insert_query)) {
                            // Query executed successfully
                            echo json_encode(array("success" => true, "message" => 'Registration successful.'));
                        } else {
                            // Error executing the query
                            echo json_encode(array("success" => false, "error" => 'Error: ' . mysqli_error($conn)));
                        }
                    }
                }
            }
        } else {
            // Required fields not provided
            echo json_encode(array("success" => false, "error" => 'All fields are required.'));
        }
    } else {
        echo json_encode(array("success" => false, "error" => 'Invalid JSON data.'));
    }
} else {
    echo json_encode(array("success" => false, "error" => 'Invalid request method.'));
}

// Close the database connection
mysqli_close($conn);
?>
