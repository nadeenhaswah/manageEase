<?php
session_start();
header('Content-Type: application/json');

// تحقق من تسجيل الدخول
if (!isset($_SESSION['id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

include('../../confing/DB_connection.php');

// تحقق من اتصال قاعدة البيانات
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// استقبال البيانات من JSON
$data = json_decode(file_get_contents("php://input"), true);

// استخراج البيانات
$comment_id = isset($data['comment_id']) ? intval($data['comment_id']) : 0;
$user_id = $_SESSION['id'];

// التحقق من الحقول المطلوبة
if (!$comment_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Comment ID is required']);
    exit;
}

try {
    // التحقق من ملكية التعليق أولاً
    $check_stmt = $conn->prepare("SELECT user_id FROM card_comments WHERE id = ?");
    $check_stmt->bind_param("i", $comment_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result()->fetch_assoc();

    if (!$check_result) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Comment not found']);
        exit;
    }

    if ($check_result['user_id'] != $user_id) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'You are not authorized to delete this comment']);
        exit;
    }

    // حذف التعليق
    $delete_stmt = $conn->prepare("DELETE FROM card_comments WHERE id = ?");
    $delete_stmt->bind_param("i", $comment_id);
    $success = $delete_stmt->execute();

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Comment deleted successfully']);
    } else {
        throw new Exception("Failed to delete comment");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
