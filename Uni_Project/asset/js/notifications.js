document.addEventListener('DOMContentLoaded', function() {
    // عرض/إخفاء الإشعارات
    const notificationsIcon = document.getElementById('notificationsIcon');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    
    notificationsIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationsDropdown.classList.toggle('show');
    });
    
    // إغلاق الإشعارات عند النقر خارجها
    document.addEventListener('click', function() {
        notificationsDropdown.classList.remove('show');
    });
    
    // تحديد الإشعار كمقروء عند النقر عليه
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-id');
            fetch('mark_notification_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'notification_id=' + notificationId
            });
            
            this.classList.remove('unread');
        });
    });
    
    // تحديد كل الإشعارات كمقروءة
    document.getElementById('markAllAsRead').addEventListener('click', function(e) {
        e.preventDefault();
        fetch('mark_all_notifications_read.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'user_id=<?= $info["id"] ?>'
        }).then(() => {
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.remove('unread');
            });
            document.querySelector('.notifications-icon .badge').style.display = 'none';
        });
    });
});