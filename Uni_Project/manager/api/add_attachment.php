<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
    $type = 'url'; // Fixed type for URL attachments
    $title = isset($_POST['title']) ? $_POST['title'] : "";
    $url = isset($_POST['url']) ? $_POST['url'] : "";

    if ($card_id <= 0 || !$title || !$url) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO card_attachments (card_id, title, type, path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $card_id, $title, $type, $url);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();

    echo json_encode(['status' => 'success', 'id' => $id, 'path' => $url]);
}
