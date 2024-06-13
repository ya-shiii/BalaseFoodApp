<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Include database connection
include 'db_connect.php';

// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the JSON input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if all required fields are set
    if (isset($data['user_id'], $data['username'], $data['password'], $data['full_name'], $data['email'], $data['phone'], $data['address'], $data['old_password'])) {
        // Sanitize the input data
        $user_id = $conn->real_escape_string($data['user_id']);
        $username = $conn->real_escape_string($data['username']);
        $password = $conn->real_escape_string($data['password']);
        $full_name = $conn->real_escape_string($data['full_name']);
        $email = $conn->real_escape_string($data['email']);
        $phone = $conn->real_escape_string($data['phone']);
        $address = $conn->real_escape_string($data['address']);
        $old_password = $conn->real_escape_string($data['old_password']);

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
    echo json_encode(array("success" => false, "error" => "Invalid request method."));
}

// Close the database connection
mysqli_close($conn);
?>
