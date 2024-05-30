<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array("success" => false, "error" => "You are not logged in."));
    exit;
}

// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the JSON input data
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // Check if all required fields are set
        if (isset($data['username']) && isset($data['password']) && isset($data['full_name']) && isset($data['email']) && isset($data['phone']) && isset($data['address']) && isset($data['old_password'])) {
            // Sanitize the input data
            $user_id = $_SESSION['user_id'];
            $username = $data['username'];
            $password = $data['password'];
            $full_name = $data['full_name'];
            $email = $data['email'];
            $phone = $data['phone'];
            $address = $data['address'];
            $old_password = $data['old_password'];

            // Verify old password
            $query = "SELECT `password` FROM customers WHERE user_id = '$user_id'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $stored_password = $row['password'];

                if ($old_password === $stored_password) {
                    // Update account details
                    $update_query = "UPDATE customers SET username = '$username', `password` = '$password', full_name = '$full_name', email = '$email', phone = '$phone', address = '$address' WHERE user_id = '$user_id'";

                    if (mysqli_query($conn, $update_query)) {
                        echo json_encode(array("success" => true, "message" => "Account details updated successfully."));
                    } else {
                        echo json_encode(array("success" => false, "error" => "Error updating account details: " . mysqli_error($conn)));
                    }
                } else {
                    echo json_encode(array("success" => false, "error" => "Old password does not match."));
                }
            } else {
                echo json_encode(array("success" => false, "error" => "User not found."));
            }
        } else {
            echo json_encode(array("success" => false, "error" => "Required fields are missing."));
        }
    } else {
        echo json_encode(array("success" => false, "error" => "Invalid JSON data."));
    }
} else {
    echo json_encode(array("success" => false, "error" => "Invalid request method."));
}

// Close the database connection
mysqli_close($conn);
?>
