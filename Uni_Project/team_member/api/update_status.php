<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($card_id <= 0 || $status === '') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    if ($status === 'Done') {
        // إذا تمت المهمة، سجل الوقت الحالي
        $stmt = $conn->prepare("UPDATE card_details SET status = ?, completed_at = NOW() WHERE card_id = ?");
    } else {
        // غير مكتملة، احذف تاريخ الإكمال
        $stmt = $conn->prepare("UPDATE card_details SET status = ?, completed_at = NULL WHERE card_id = ?");
    }

    $stmt->bind_param("si", $status, $card_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode(['status' => 'success']);
}
