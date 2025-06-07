<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list_id = isset($_POST['list_id']) ? intval($_POST['list_id']) : 0;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';

    if ($list_id <= 0 || empty($title)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO cards (list_id, title) VALUES (?, ?)");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("is", $list_id, $title);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'card' => [
                'id' => $stmt->insert_id,
                'list_id' => $list_id,
                'title' => htmlspecialchars($title)
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
}
