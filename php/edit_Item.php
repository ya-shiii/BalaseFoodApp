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
    // Extract form data from $_POST and $_FILES
    $itemId = $_POST['item_id'];
    $itemName = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $filename = '';
    $imgPath = '';

    // Check for duplicate item name
    if (isDuplicateItem($conn, $itemName, $itemId)) {
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

        // Move the uploaded file to the desired destination
        if (!move_uploaded_file($fileTmpPath, $destination)) {
            $response = array(
                'success' => false,
                'message' => 'Error uploading file. Please check the file permissions and ensure the destination directory exists.'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        } else {
            $imgPath = 'img/menu/' . $filename; // Set the relative path to the image
        }
    }

    // Update the database
    $update_query = "UPDATE menu_list SET name='$itemName', description='$description', price='$price', category='$category'";

    // Include img_path in the update query if a new image was uploaded
    if (!empty($imgPath)) {
        $update_query .= ", img_path='$imgPath'";
    }

    $update_query .= " WHERE item_id=$itemId";

    if ($conn->query($update_query) === TRUE) {
        $response = array(
            'success' => true,
            'message' => 'Item updated successfully.'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $response = array(
            'success' => false,
            'message' => 'Error updating item: ' . $conn->error
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    $conn->close();
    exit();
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
