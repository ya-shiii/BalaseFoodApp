<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $putData = file_get_contents("php://input");

    $formData = parse_multipart_formdata($putData);

    $response = [];

    $item_id = $formData['item_id'] ?? '';
    $name = $formData['name'] ?? '';
    $description = $formData['description'] ?? '';
    $price = $formData['price'] ?? '';
    $category = $formData['category'] ?? '';
    $file = $formData['fileToUpload'] ?? '';
    if (isset($formData['fileToUpload']) && $file['name'] != null && $file['name'] != 'empty.jpg') {

        $response['Uploaded file name'] = $file['name'];
        $response['Uploaded file type'] = $file['type'];

        $fileContentBase64 = base64_encode(file_get_contents($file['tmp_name']));
        $filename = str_replace(' ', '_', $name) . '.jpg';
        $uploadDirectory = realpath(__DIR__ . "../../img/menu") . DIRECTORY_SEPARATOR;
        $destination = $uploadDirectory . $filename;

        $decodedContent = base64_decode($fileContentBase64);
        if (file_put_contents($destination, $decodedContent)) {
            $response['file_upload_status'] = "success";
            $response['file_destination'] = $destination;
        } else {
            $response['file_upload_status'] = "error";
        }
    }

    // Construct SQL query
    $sql = "UPDATE menu_list SET `name`=?, `description`=?, price=?, category=?";
    $params = array($name, $description, $price, $category);


    $sql .= " WHERE item_id=?";
    $params[] = $item_id;

    $stmt = $conn->prepare($sql);
    $types = str_repeat('s', count($params) - 1) . 's'; 
    $stmt->bind_param($types, ...$params); 

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to menu information';
    }

    $stmt->close();

    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>

