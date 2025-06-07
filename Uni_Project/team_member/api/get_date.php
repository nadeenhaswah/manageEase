<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $card_id = isset($_GET['card_id']) ? intval($_GET['card_id']) : 0;

    if ($card_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid card ID']);
        exit;
    }

    $stmt = $conn->prepare("SELECT start_date, due_date, due_reminder FROM card_details WHERE card_id = ?");
    $stmt->bind_param("i", $card_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['status' => 'success', 'dates' => $row]);
    } else {
        echo json_encode(['status' => 'empty']);
    }

    $stmt->close();
    $conn->close();
}
