<?php
// get_attachments.php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['card_id'])) {
    $card_id = intval($_GET['card_id']);
    if ($card_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid card ID']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, title, type, path FROM card_attachments WHERE card_id = ?");
    $stmt->bind_param("i", $card_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $attachments = [];
    while ($row = $result->fetch_assoc()) {
        $attachments[] = $row;
    }

    echo json_encode(['status' => 'success', 'attachments' => $attachments]);
}
