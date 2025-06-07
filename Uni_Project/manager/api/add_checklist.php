<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
    $title = isset($_POST['title']) ? ($_POST['title']) : "";

    if ($card_id <= 0 || $title === '') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO card_checklists (card_id, title) VALUES (?, ?)");
    $stmt->bind_param("is", $card_id, $title);
    $stmt->execute();
    $inserted_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();

    echo json_encode(['status' => 'success', 'checklist_id' => $inserted_id]);
}
