<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['card_id'])) {
    $card_id = intval($_GET['card_id']);

    $stmt = $conn->prepare("
        SELECT u.username, u.profile_picture
        FROM card_members cm
        JOIN users u ON cm.username = u.username
        WHERE cm.card_id = ?
    ");
    $stmt->bind_param("i", $card_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    echo json_encode(['status' => 'success', 'members' => $members]);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid card ID']);
