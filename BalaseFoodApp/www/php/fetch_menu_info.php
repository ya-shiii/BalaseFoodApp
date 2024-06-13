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

// Check if item_id is provided
if (isset($_POST['item_id'])) {
    // Sanitize the input to prevent SQL injection
    $item_id = $_POST['item_id'];

    // Query to fetch Item information
    $query = "SELECT * FROM menu_list WHERE item_id = $item_id"; 
    $result = $conn->query($query);

    // Check if the query was successful
    if ($result) {
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            // Fetch the Item information
            $menu_info = $result->fetch_assoc();

            // Return the driver information as JSON
            echo json_encode($menu_info);
        } else {
            // No matching Item found
            echo json_encode(['error' => 'Item not found']);
        }
    } else {
        // Query execution failed
        echo json_encode(['error' => 'Query failed']);
    }
} else {
    // u_id parameter is missing
    echo json_encode(['error' => 'Missing id parameter']);
}
?>
