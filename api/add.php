<?php
include 'config.php';

function isDuplicateItem($conn, $itemName) {
    $query = "SELECT COUNT(*) AS count FROM menu_list WHERE name = '$itemName'";
    $result = $conn->query($query);
    if (!$result) {
        // Error executing the query
        return array('success' => false, 'message' => 'Error executing query: ' . $conn->error);
    }
    $row = $result->fetch_assoc();
    return array('success' => true, 'isDuplicate' => $row['count'] > 0);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemName = $conn->real_escape_string($_POST['item_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $filename = '';

    $duplicateCheck = isDuplicateItem($conn, $itemName);
    if ($duplicateCheck['success']) {
        if ($duplicateCheck['isDuplicate']) {
            echo json_encode(array('success' => false, 'message' => 'Item with the same name already exists.'));
            exit();
        }
    } else {
        echo json_encode(array('success' => false, 'message' => $duplicateCheck['message']));
        exit();
    }

    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $extension = pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION);
        $filename = str_replace(' ', '_', $itemName) . '.' . $extension;
        $fileTmpPath = $_FILES['item_image']['tmp_name'];
        $destination = '../img/menu/' . $filename;

        if (!move_uploaded_file($fileTmpPath, $destination)) {
            echo json_encode(array('success' => false, 'message' => 'Error uploading file.'));
            exit();
        }
    }

    $insert_query = "INSERT INTO menu_list (name, description, price, category) VALUES ('$itemName', '$description', '$price', '$category')";
    if ($conn->query($insert_query) === TRUE) {
        echo json_encode(array('success' => true, 'message' => 'New item added successfully.'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error adding new item: ' . $conn->error));
    }

    $conn->close();
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
}
?>
