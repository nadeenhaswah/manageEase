<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if ($card_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid card ID']);
        exit;
    }

    // تحقق إذا كان هناك سجل موجود مسبقًا
    $check = $conn->prepare("SELECT id FROM card_details WHERE card_id = ?");
    $check->bind_param("i", $card_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // تحديث
        $stmt = $conn->prepare("UPDATE card_details SET description = ? WHERE card_id = ?");
        $stmt->bind_param("si", $description, $card_id);
    } else {
        // إدخال جديد
        $stmt = $conn->prepare("INSERT INTO card_details (card_id, description) VALUES (?, ?)");
        $stmt->bind_param("is", $card_id, $description);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

    $stmt->close();
    $check->close();
    $conn->close();
}
