<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

    if ($project_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid project ID']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, name FROM lists WHERE project_id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $lists = [];
    while ($row = $result->fetch_assoc()) {
        $lists[] = $row;
    }

    echo json_encode(['status' => 'success', 'lists' => $lists]);

    $stmt->close();
    $conn->close();
}
