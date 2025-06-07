<?php
include('../confing/DB_connection.php');
session_start();
$flag = 0;
$user = $_SESSION['username'];
$id = $_SESSION['id'];

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit;
}


$search_enabled = isset($_POST['search_enabled']) && $_POST['search_enabled'] == '1' ? 1 : 0;

$query = "UPDATE users SET search_enabled = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $search_enabled, $id);

if ($stmt->execute()) {
    echo "Success";
} else {
    http_response_code(500);
    echo "Error";
}
