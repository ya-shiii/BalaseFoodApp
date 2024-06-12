<?php

include 'config.php';

$chefs = array();

$query = "SELECT * FROM menu_list";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
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
    echo json_encode(array());
    exit();
}

$conn->close();

echo json_encode($chefs);
