<?php
session_start();
include 'db_connect.php';

// Initialize response array
$response = array();

// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get the JSON input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        $response['success'] = false;
        $response['message'] = 'You are not logged in.';
    } else {
        // Check if all required fields are set
        if (isset($data['username']) && isset($data['password']) && isset($data['full_name']) && isset($data['email']) && isset($data['phone']) && isset($data['address']) && isset($data['old_password'])) {
            // Sanitize the input data
            $user_id = $_SESSION['user_id'];
            $username = mysqli_real_escape_string($conn, $data['username']);
            $password = mysqli_real_escape_string($conn, $data['password']);
            $full_name = mysqli_real_escape_string($conn, $data['full_name']);
            $email = mysqli_real_escape_string($conn, $data['email']);
            $phone = mysqli_real_escape_string($conn, $data['phone']);
            $address = mysqli_real_escape_string($conn, $data['address']);
            $old_password = mysqli_real_escape_string($conn, $data['old_password']);

            // Verify old password
            $query = "SELECT `password` FROM in_charge WHERE user_id = '$user_id'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $stored_password = $row['password'];

                if ($old_password === $stored_password) {
                    // Update account details
                    $update_query = "UPDATE in_charge SET username = '$username', `password` = '$password', full_name = '$full_name', email = '$email', phone = '$phone', address = '$address' WHERE user_id = '$user_id'";

                    if (mysqli_query($conn, $update_query)) {
                        $response['success'] = true;
                        $response['message'] = 'Account details updated successfully.';
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Error updating account details: ' . mysqli_error($conn);
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Old password does not match.';
                }
            } else {
                $response['success'] = false;
                $response['message'] = 'User not found.';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Required fields are missing.';
        }
    }
} else {
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
