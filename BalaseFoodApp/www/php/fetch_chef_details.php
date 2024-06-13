<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

include 'db_connect.php';

// Check if the user_id is provided
if (!isset($_GET['user_id'])) {
    echo json_encode(array("success" => false, "error" => "User ID not provided"));
    exit();
}

$user_id = $_GET['user_id'];

// Fetch account details from the database
$query = "SELECT * FROM in_charge WHERE user_id = '$user_id'";
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

$conn->close();
?>
