<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['checklist_id'])) {
    $checklist_id = intval($_GET['checklist_id']);

    $stmt = $conn->prepare("SELECT * FROM card_checklist_items WHERE checklist_id = ?");
    $stmt->bind_param("i", $checklist_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    echo json_encode(['status' => 'success', 'items' => $items]);
}
