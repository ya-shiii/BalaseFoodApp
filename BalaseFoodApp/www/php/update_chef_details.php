<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

include 'db_connect.php';

// Check if the user_id is provided
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['user_id'])) {
    echo json_encode(array("success" => false, "error" => "User ID not provided"));
    exit();
}

$user_id = $data['user_id'];

// Check if all required fields are set
if (isset($data['username'], $data['password'], $data['full_name'], $data['email'], $data['phone'], $data['address'])) {
    // Sanitize the input data
    $username = $conn->real_escape_string($data['username']);
    $password = $conn->real_escape_string($data['password']);
    $full_name = $conn->real_escape_string($data['full_name']);
    $email = $conn->real_escape_string($data['email']);
    $phone = $conn->real_escape_string($data['phone']);
    $address = $conn->real_escape_string($data['address']);

    // Update account details
    $update_query = "UPDATE in_charge SET username = '$username', `password` = '$password', full_name = '$full_name', email = '$email', phone = '$phone', address = '$address' WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $update_query)) {
        echo json_encode(array("success" => true, "message" => "Account details updated successfully."));
    } else {
        echo json_encode(array("success" => false, "error" => "Error updating account details: " . mysqli_error($conn)));
    }
} else {
    echo json_encode(array("success" => false, "error" => "Required fields are missing."));
}

// Close the database connection
mysqli_close($conn);
?>
