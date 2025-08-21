<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-bell me-2 text-warning"></i>Notifications
                </h1>
                <p class="text-muted mb-0">View and manage system notifications.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-success btn-sm" id="markAllRead">
                        <i class="fas fa-check-double me-1"></i>Mark All as Read
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($notifications)): ?>
                    <div class="list-group">
                        <?php foreach ($notifications as $notification): ?>
                        <div class="list-group-item list-group-item-action <?php echo !$notification->is_read ? 'list-group-item-warning' : ''; ?>">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($notification->title); ?></h6>
                                        <?php if (!$notification->is_read): ?>
                                            <span class="badge bg-danger ms-2">New</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mb-1"><?php echo htmlspecialchars($notification->message); ?></p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo date('M j, Y \a\t g:i A', strtotime($notification->created_at)); ?>
                                    </small>
                                </div>
                                <div class="ms-3 d-flex align-items-center gap-2">
                                    <?php
                                    $type_colors = [
                                        'info' => 'info',
                                        'success' => 'success',
                                        'warning' => 'warning',
                                        'danger' => 'danger'
                                    ];
                                    $color = isset($type_colors[$notification->type]) ? $type_colors[$notification->type] : 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $color; ?>">
                                        <?php echo ucfirst($notification->type); ?>
                                    </span>
                                    <?php if (!$notification->is_read): ?>
                                        <button type="button" class="btn btn-sm btn-outline-success mark-read" data-notification-id="<?php echo $notification->id; ?>">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($pagination)): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo $pagination; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No notifications found</h5>
                        <p class="text-muted">You don't have any notifications at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark individual notification as read
    document.querySelectorAll('.mark-read').forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-notification-id');
            markNotificationAsRead(notificationId, this);
        });
    });
    
    // Mark all notifications as read
    document.getElementById('markAllRead').addEventListener('click', function() {
        if (confirm('Mark all notifications as read?')) {
            markAllNotificationsAsRead();
        }
    });
    
    function markNotificationAsRead(notificationId, button) {
        fetch('<?php echo base_url('staff/mark_notification_read'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                const notificationItem = button.closest('.list-group-item');
                notificationItem.classList.remove('list-group-item-warning');
                
                // Remove "New" badge
                const newBadge = notificationItem.querySelector('.badge.bg-danger');
                if (newBadge) {
                    newBadge.remove();
                }
                
                // Remove mark as read button
                button.remove();
                
                // Show success message
                showAlert('Notification marked as read', 'success');
            } else {
                showAlert(data.message || 'Failed to mark notification as read', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Network error occurred', 'error');
        });
    }
    
    function markAllNotificationsAsRead() {
        fetch('<?php echo base_url('staff/mark_all_notifications_read'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                document.querySelectorAll('.list-group-item-warning').forEach(item => {
                    item.classList.remove('list-group-item-warning');
                });
                
                // Remove all "New" badges
                document.querySelectorAll('.badge.bg-danger').forEach(badge => {
                    badge.remove();
                });
                
                // Remove all mark as read buttons
                document.querySelectorAll('.mark-read').forEach(button => {
                    button.remove();
                });
                
                // Hide mark all as read button
                document.getElementById('markAllRead').style.display = 'none';
                
                // Show success message
                showAlert('All notifications marked as read', 'success');
            } else {
                showAlert(data.message || 'Failed to mark all notifications as read', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Network error occurred', 'error');
        });
    }
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at the top of the card body
        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(alertDiv, cardBody.firstChild);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 