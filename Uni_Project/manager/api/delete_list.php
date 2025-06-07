<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list_id = isset($_POST['list_id']) ? intval($_POST['list_id']) : 0;

    if ($list_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid list ID']);
        exit;
    }

    // حذف البطاقات المرتبطة بالقائمة (مهم)
    $deleteCards = $conn->prepare("DELETE FROM cards WHERE list_id = ?");
    $deleteCards->bind_param("i", $list_id);
    $deleteCards->execute();
    $deleteCards->close();

    // حذف القائمة
    $stmt = $conn->prepare("DELETE FROM lists WHERE id = ?");
    $stmt->bind_param("i", $list_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
}
