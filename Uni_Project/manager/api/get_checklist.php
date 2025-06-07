<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['card_id'])) {
    $card_id = intval($_GET['card_id']);

    $stmt = $conn->prepare("SELECT * FROM card_checklists WHERE card_id = ?");
    $stmt->bind_param("i", $card_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $checklists = [];
    while ($row = $result->fetch_assoc()) {
        $checklists[] = $row;
    }

    echo json_encode(['status' => 'success', 'checklists' => $checklists]);
}
