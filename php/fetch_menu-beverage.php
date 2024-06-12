<?php

// Include the database connection
include 'db_connect.php';

// Initialize an array to store chefss
$chefs = array();

// Query to fetch chefss from in_charge table
$query = "SELECT * FROM menu_list WHERE category = 'Beverage' AND status = 'Available'";
$result = $conn->query($query);

// Check if query was successful
if ($result && $result->num_rows > 0) {
    // Fetch chefss and add them to the array
    while ($row = $result->fetch_assoc()) {
        
        // Generate the filename string
        $filename = str_replace(' ', '_', $row['name']) . '.jpg';


        $chef = array(
            'item_id' => $row['item_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'category' => $row['category'],
            'filename' => $filename
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
