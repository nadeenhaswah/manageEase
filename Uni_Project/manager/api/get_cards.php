<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $list_id = isset($_GET['list_id']) ? intval($_GET['list_id']) : 0;

    if ($list_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid list ID']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, title FROM cards WHERE list_id = ?");
    $stmt->bind_param("i", $list_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cards = [];
    while ($row = $result->fetch_assoc()) {
        $cards[] = $row;
    }

    echo json_encode(['status' => 'success', 'cards' => $cards]);

    $stmt->close();
    $conn->close();
}
