<?php
// وظائف الإشعارات
function sendNotification($conn, $user_id, $triggered_by, $project_id, $task_id, $type, $message, $related_url = null, $is_actionable = false, $action_url = null)
{
    $sql = "INSERT INTO notifications (user_id, triggered_by, project_id, task_id, type, message, related_url, is_actionable, action_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiiisssis", $user_id, $triggered_by, $project_id, $task_id, $type, $message, $related_url, $is_actionable, $action_url);
    return mysqli_stmt_execute($stmt);
}

// الحصول على الإشعارات غير المقروءة للمستخدم
function getUnreadNotifications($conn, $user_id)
{
    $sql = "SELECT n.*, u.username as triggered_by_name, u.profile_picture as triggered_by_avatar 
            FROM notifications n 
            JOIN users u ON n.triggered_by = u.id 
            WHERE n.user_id = ? AND n.is_read = 0 
            ORDER BY n.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

// تحديد الإشعار كمقروء
function markNotificationAsRead($conn, $notification_id)
{
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $notification_id);
    return mysqli_stmt_execute($stmt);
}
