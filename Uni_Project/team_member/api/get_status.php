<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['card_id'])) {
    $card_id = intval($_GET['card_id']);

    $stmt = $conn->prepare("SELECT status, completed_at FROM card_details WHERE card_id = ?");
    $stmt->bind_param("i", $card_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'status' => 'success',
            'card_status' => $row['status'],
            'completed_at' => $row['completed_at']
        ]);
    } else {
        echo json_encode(['status' => 'success', 'card_status' => null]); // لا يوجد بعد
    }

    $stmt->close();
    $conn->close();
    exit;
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
