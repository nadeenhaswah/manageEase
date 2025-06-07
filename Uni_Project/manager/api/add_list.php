<?php
include('../../confing/DB_connection.php');
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = intval($_POST['project_id']);
    $name = trim($_POST['name']);

    if (empty($name) || $project_id <= 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO lists (project_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $project_id, $name);

    if ($stmt->execute()) {
        $list_id = $stmt->insert_id;
        echo json_encode([
            'status' => 'success',
            'list' => [
                'id' => $list_id,
                'name' => htmlspecialchars($name),
                'project_id' => $project_id
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }

    $stmt->close();
}
