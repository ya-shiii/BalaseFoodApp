<?php

include('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $putData = file_get_contents("php://input");

    $formData = parse_multipart_formdata($putData);
    $item_id = $formData['item_id'] ?? '';
    $stmt = $conn->prepare("DELETE FROM menu_list WHERE item_id = ?");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete menu']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
