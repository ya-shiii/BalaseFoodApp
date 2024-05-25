<?php
// Include the database connection
include 'db_connect.php';

// Function to check for duplicate item names
function isDuplicateItem($conn, $itemName, $itemId)
{
    $query = "SELECT COUNT(*) AS count FROM menu_list WHERE name = '$itemName' AND item_id != $itemId";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    return false;
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = $_POST['item_id'];
    $itemName = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $filename = '';

    // Check for duplicate item name
    if (isDuplicateItem($conn, $itemName, $itemId)) {
        echo '<script>alert("Item with the same name already exists.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        exit();
    }

    // Check if file is uploaded
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $extension = pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION);
        $filename = str_replace(' ', '_', $itemName) . '.' . $extension;
        $fileTmpPath = $_FILES['item_image']['tmp_name'];
        $destination = '../img/menu/' . $filename;

        // Remove the old file if it exists
        if (file_exists($destination)) {
            if (!unlink($destination)) {
                echo '<script>alert("Error removing old file.");</script>';
                echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
                exit();
            }
        }

        // Move the uploaded file to the desired destination
        if (!move_uploaded_file($fileTmpPath, $destination)) {
            error_log('File upload error: ' . print_r($_FILES['item_image']['error'], true));
            echo '<script>alert("Error uploading file. Please check the file permissions and ensure the destination directory exists.");</script>';
            echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
            exit();
        }
    }

    // Update the database
    $update_query = "UPDATE menu_list SET name='$itemName', description='$description', price='$price', category='$category' WHERE item_id=$itemId";


    if ($conn->query($update_query) === TRUE) {
        echo '<script>alert("Item updated successfully.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
    } else {
        error_log('Error updating database: ' . $conn->error);
        echo '<script>alert("Error updating item.");</script>';
        echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';

    }

    $conn->close();
} else {
    echo '<script>alert("Invalid request method.");</script>';
    echo '<script>window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
}
?>