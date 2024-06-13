<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Include database connection
include 'db_connect.php';

// Check if user_id is provided in GET request
if (!isset($_GET['user_id'])) {
    echo json_encode(array("success" => false, "error" => "User ID not provided"));
    exit();
}

// Safely fetch the user_id from GET parameters
$user_id = $conn->real_escape_string($_GET['user_id']);

// Fetch account details from the database
$query = "SELECT * FROM customers WHERE user_id = '$user_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(
        array(
            "success" => true,
            "user_id" => $row['user_id'],
            "username" => $row['username'],
            "password" => $row['password'],
            "full_name" => $row['full_name'],
            "email" => $row['email'],
            "phone" => $row['phone'],
            "address" => $row['address']
        )
    );
} else {
    echo json_encode(array("success" => false, "error" => "No account details found"));
}

// Close the connection
$conn->close();
?>
