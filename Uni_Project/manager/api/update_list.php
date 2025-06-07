<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list_id = isset($_POST['list_id']) ? intval($_POST['list_id']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if ($list_id <= 0 || empty($name)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE lists SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $list_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
}
