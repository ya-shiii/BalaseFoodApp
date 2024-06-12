<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['menu_id'])) {
    $art_id = $_GET['menu_id'];

    $stmt = $conn->prepare("SELECT * FROM menu_list WHERE item_id = ?");
    $stmt->bind_param("i", $art_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $menu = $result->fetch_assoc();
        $menu['filename'] = str_replace(' ', '_', $menu['name']) . '.jpg';
        echo json_encode($menu);
    } else {
        echo json_encode(['success' => false, 'message' => 'Menu not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
