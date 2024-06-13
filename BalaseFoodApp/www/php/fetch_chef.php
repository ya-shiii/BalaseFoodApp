<?php 
// Allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}



// Include the database connection
include 'db_connect.php';

// Initialize an array to store chefss
$chefs = array();

// Query to fetch chefss from in_charge table
$query = "SELECT * FROM in_charge";
$result = $conn->query($query);

// Check if query was successful
if ($result && $result->num_rows > 0) {
    // Fetch chefss and add them to the array
    while ($row = $result->fetch_assoc()) {
        $chef = array(
            'u_id' => $row['user_id'],
            'full_name' => $row['full_name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'address' => $row['address'],
        );
        $chefs[] = $chef;
    }
} else {
    // No chefss found
    echo json_encode(array());
    exit(); // Stop further execution
}

// Close the database connection
$conn->close();

// Encode the array as JSON and echo
echo json_encode($chefs);
