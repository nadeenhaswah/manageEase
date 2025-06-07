<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid checklist ID']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM card_checklists WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode(['status' => 'success']);
}
