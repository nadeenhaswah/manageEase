<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';

    if ($id <= 0 || $title === '') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE card_checklists SET title = ? WHERE id = ?");
    $stmt->bind_param("si", $title, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo json_encode(['status' => 'success']);
}
