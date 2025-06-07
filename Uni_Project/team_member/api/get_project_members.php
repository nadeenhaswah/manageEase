<?php
include('../../confing/DB_connection.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $project_id = intval($_GET['id']);

    // استرجاع أسماء الأعضاء من جدول المشاريع
    $stmt = $conn->prepare("SELECT members FROM projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $memberNames = explode(",", $row['members']);

        if (count($memberNames) > 0) {
            // تجهيز placeholders
            $placeholders = implode(',', array_fill(0, count($memberNames), '?'));
            $types = str_repeat('s', count($memberNames));

            // استعلام لاسترجاع صور وأسماء الأعضاء
            $stmt = $conn->prepare("SELECT id,username, profile_picture FROM users WHERE username IN ($placeholders)");
            $stmt->bind_param($types, ...$memberNames);
            $stmt->execute();
            $res = $stmt->get_result();

            $members = [];
            while ($member = $res->fetch_assoc()) {
                $members[] = $member;
            }

            echo json_encode(['status' => 'success', 'members' => $members]);
            exit;
        }
    }
}

// في حال وجود خطأ
http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Invalid project or no members']);
