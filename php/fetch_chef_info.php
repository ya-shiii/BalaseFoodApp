<?php

// Include the database connection
include 'db_connect.php';

// Check if u_id is provided
if (isset($_POST['u_id'])) {
    // Sanitize the input to prevent SQL injection
    $u_id = $_POST['u_id'];

    // Query to fetch Chef information
    $query = "SELECT * FROM in_charge WHERE user_id = $u_id"; 
    $result = $conn->query($query);

    // Check if the query was successful
    if ($result) {
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            // Fetch the Chef information
            $chef_info = $result->fetch_assoc();

            // Return the driver information as JSON
            echo json_encode($chef_info);
        } else {
            // No matching Chef found
            echo json_encode(['error' => 'Chef not found']);
        }
    } else {
        // Query execution failed
        echo json_encode(['error' => 'Query failed']);
    }
} else {
    // u_id parameter is missing
    echo json_encode(['error' => 'Missing u_id parameter']);
}
?>
