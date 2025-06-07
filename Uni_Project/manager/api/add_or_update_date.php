<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : null;
    $due_reminder = isset($_POST['due_reminder']) ? $_POST['due_reminder'] : 'None';


    if ($card_id <= 0 || !$start_date || !$due_date) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // تأكد إن كانت البطاقة لها تفاصيل بالفعل
    $check = $conn->prepare("SELECT id FROM card_details WHERE card_id = ?");
    $check->bind_param("i", $card_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // تحديث
        $stmt = $conn->prepare("UPDATE card_details SET start_date = ?, due_date = ?, due_reminder = ? WHERE card_id = ?");
        $stmt->bind_param("sssi", $start_date, $due_date, $due_reminder, $card_id);
    } else {
        // إدخال جديد
        $stmt = $conn->prepare("INSERT INTO card_details (card_id, start_date, due_date, due_reminder) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $card_id, $start_date, $due_date, $due_reminder);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
}
