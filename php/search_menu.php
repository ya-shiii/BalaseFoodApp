<?php

// Include the database connection
include 'db_connect.php';

// Get the search query
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Initialize an array to store items
$items = array();

// Prepare the SQL statement with a wildcard search
$query = "SELECT * FROM menu_list WHERE name LIKE '%$searchQuery%'";
$result = $conn->query($query);

// Check if query was successful
if ($result && $result->num_rows > 0) {
    // Fetch items and add them to the array
    while ($row = $result->fetch_assoc()) {
        
        // Generate the filename string
        $filename = str_replace(' ', '_', $row['name']) . '.jpg';

        $item = array(
            'item_id' => $row['item_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'category' => $row['category'],
            'filename' => $filename
        );
        $items[] = $item;
    }
} else {
    // No items found
    echo json_encode(array());
    exit(); // Stop further execution
}

// Close the database connection
$conn->close();

// Encode the array as JSON and echo
echo json_encode($items);
?>
