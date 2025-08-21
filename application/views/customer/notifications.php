<?php
$this->load->view('customer/common/header');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-bell me-2"></i>Notifications
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
            <i class="fas fa-check-double me-1"></i>Mark All as Read
        </button>
    </div>
</div>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Notifications List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>All Notifications
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($notifications)): ?>
            <div class="list-group list-group-flush">
                <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item list-group-item-action <?php echo !$notification->is_read ? 'list-group-item-warning' : ''; ?>" 
                         data-notification-id="<?php echo $notification->id; ?>">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <h6 class="mb-0 me-2"><?php echo htmlspecialchars($notification->title); ?></h6>
                                    <?php if (!$notification->is_read): ?>
                                        <span class="badge bg-danger">New</span>
                                    <?php endif; ?>
                                    <span class="badge bg-<?php echo $notification->type; ?> ms-2"><?php echo ucfirst($notification->category); ?></span>
                                </div>
                                <p class="mb-1 text-muted"><?php echo htmlspecialchars($notification->message); ?></p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo date('M j, Y g:i A', strtotime($notification->created_at)); ?>
                                </small>
                            </div>
                            <div class="ms-3">
                                <?php if (!$notification->is_read): ?>
                                    <button class="btn btn-sm btn-outline-success" onclick="markAsRead(<?php echo $notification->id; ?>)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <h5>No Notifications</h5>
                <p class="text-muted">You don't have any notifications yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch('<?php echo base_url('customer/mark_notification_read'); ?>', {
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
            // Update the notification item
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('list-group-item-warning');
                const badge = notificationItem.querySelector('.badge.bg-danger');
                if (badge) badge.remove();
                const button = notificationItem.querySelector('.btn-outline-success');
                if (button) button.remove();
            }
        } else {
            alert('Failed to mark notification as read');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark notification as read');
    });
}

function markAllAsRead() {
    if (confirm('Are you sure you want to mark all notifications as read?')) {
        fetch('<?php echo base_url('customer/mark_all_notifications_read'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to mark all notifications as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to mark all notifications as read');
        });
    }
}
</script>

<?php $this->load->view('customer/common/footer'); ?> 