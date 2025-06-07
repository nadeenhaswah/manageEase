<?php
// notifications.php
include('../confing/DB_connection.php');
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header('Location: ../auth/logIn.php');
    exit();
}

$user_id = $_SESSION['id'];

// جلب الإشعارات الغير مقروءة للمستخدم الحالي
$sql = "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);

// تحديث الإشعارات كمقروءة عند طلب AJAX
if (isset($_POST['mark_as_read']) && $_POST['mark_as_read'] == 'true') {
    $update_sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $user_id);
    $update_stmt->execute();
    exit();
}
?>

<div class="notifications-dropdown">
    <div class="notifications-header">
        <h5>Notifications</h5>
        <button id="mark-all-read">Mark all as read</button>
    </div>
    <div class="notifications-list">
        <?php if (empty($notifications)): ?>
            <div class="notification-item">No new notifications</div>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item <?= $notification['is_read'] ? 'read' : 'unread' ?>">
                    <div class="notification-icon">
                        <?php
                        $icon = '';
                        switch ($notification['type']) {
                            case 'member_added':
                                $icon = 'fa-user-plus';
                                break;
                            case 'task_assigned':
                                $icon = 'fa-tasks';
                                break;
                            case 'task_status_changed':
                                $icon = 'fa-exchange-alt';
                                break;
                            case 'attachment_uploaded':
                                $icon = 'fa-paperclip';
                                break;
                            case 'project_status_changed':
                                $icon = 'fa-project-diagram';
                                break;
                            case 'task_comment':
                                $icon = 'fa-comment';
                                break;
                            case 'deadline_reminder':
                                $icon = 'fa-clock';
                                break;
                            case 'task_submitted':
                                $icon = 'fa-check-circle';
                                break;
                            default:
                                $icon = 'fa-bell';
                        }
                        ?>
                        <i class="fas <?= $icon ?>"></i>
                    </div>
                    <div class="notification-content">
                        <p><?= htmlspecialchars($notification['message']) ?></p>
                        <small><?= date('M d, Y h:i A', strtotime($notification['created_at'])) ?></small>
                    </div>
                    <?php if ($notification['related_url']): ?>
                        <a href="<?= htmlspecialchars($notification['related_url']) ?>" class="notification-link"></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Mark all as read
        $('#mark-all-read').click(function() {
            $.post('notifications.php', {
                mark_as_read: 'true'
            }, function() {
                $('.notification-item').removeClass('unread').addClass('read');
                $('.notifications .badge').text('0');
            });
        });
    });
</script>