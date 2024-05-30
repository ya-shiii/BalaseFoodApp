<?php
// Include the database connection
include 'db_connect.php';

// Function to check for duplicate item names
function isDuplicateItem($conn, $itemName) {
    $query = "SELECT COUNT(*) AS count FROM menu_list WHERE name = '$itemName'";
    $result = $conn->query($query);
    if (!$result) {
        // Error executing the query
        die('Error executing query: ' . $conn->error);
    }
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract form data
    $itemName = $conn->real_escape_string($_POST['item_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $filename = '';

    // Check for duplicate item name
    if (isDuplicateItem($conn, $itemName)) {
        $response = array(
            'success' => false,
            'message' => 'Item with the same name already exists.'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Check if file is uploaded
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $extension = pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION);
        $filename = str_replace(' ', '_', $itemName) . '.' . $extension;
        $fileTmpPath = $_FILES['item_image']['tmp_name'];
        $destination = '../img/menu/' . $filename;

        if (!move_uploaded_file($fileTmpPath, $destination)) {
            $response = array(
                'success' => false,
                'message' => 'Error uploading file.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }

    // Insert into database
    $insert_query = "INSERT INTO menu_list (name, description, price, category) VALUES ('$itemName', '$description', '$price', '$category')";
    if ($conn->query($insert_query) === TRUE) {
        $response = array(
            'success' => true,
            'message' => 'New item added successfully.'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        $response = array(
            'success' => false,
            'message' => 'Error adding new item: ' . $conn->error
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $conn->close();
} else {
    $response = array(
        'success' => false,
        'message' => 'Invalid request method.'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
