<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';

    if ($card_id <= 0 || empty($title)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE cards SET title = ? WHERE id = ?");
    $stmt->bind_param("si", $title, $card_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
}
