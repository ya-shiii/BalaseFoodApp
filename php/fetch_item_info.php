<?php

// Include the database connection
include 'db_connect.php';

// Initialize an array to store item info
$item_info = array();

// Check if item_id is provided
if (isset($_POST['item_id'])) {
    // Sanitize the input to prevent SQL injection
    $item_id = intval($_POST['item_id']);

    // Query to fetch item information
    $query = "SELECT * FROM menu_list WHERE item_id = $item_id";
    $result = $conn->query($query);

    // Check if the query was successful
    if ($result) {
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            // Fetch the item information
            $item = $result->fetch_assoc();

            // Generate the filename string
            $filename = str_replace(' ', '_', $item['name']) . '.jpg';

            $item_info = array(
                'item_id' => $item['item_id'],
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'category' => $item['category'],
                'filename' => $filename
            );

            // Return the item information as JSON
            echo json_encode($item_info);
        } else {
            // No matching item found
            echo json_encode(['error' => 'Item not found']);
        }
    } else {
        // Query execution failed
        echo json_encode(['error' => 'Query failed']);
    }
} else {
    // item_id parameter is missing
    echo json_encode(['error' => 'Missing item_id parameter']);
}

// Close the database connection
$conn->close();
?>
