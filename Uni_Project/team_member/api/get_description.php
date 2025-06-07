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

    $stmt = $conn->prepare("SELECT description FROM card_details WHERE card_id = ?");
    $stmt->bind_param("i", $card_id);
    $stmt->execute();
    $stmt->bind_result($description);

    if ($stmt->fetch()) {
        echo json_encode(['status' => 'success', 'description' => $description]);
    } else {
        echo json_encode(['status' => 'success', 'description' => '']);
    }

    $stmt->close();
    $conn->close();
}
