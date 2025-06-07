<?php
include('../../confing/DB_connection.php');
header('Content-Type: application/json');

$projectId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($projectId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid project ID']);
    exit;
}

$stmt = $conn->prepare("SELECT start_date, end_date FROM projects WHERE id = ?");
$stmt->bind_param("i", $projectId);
$stmt->execute();
$result = $stmt->get_result();
$dates = $result->fetch_assoc();

echo json_encode(['status' => 'success', 'dates' => $dates]);
