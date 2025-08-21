<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Staff Panel'; ?> - USA Pharmacy 365 Staff</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Staff Admin Styles -->
    <style>
        .navbar {
            background-color: #343a40;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            transition: background-color 0.3s ease;
        }
        
        .navbar.day-mode {
            background-color: #ffffff;
            color: #343a40;
        }
        
        .navbar.night-mode {
            background-color: #343a40;
            color: #ffffff;
        }
        
        .navbar.day-mode .nav-link {
            color: #343a40 !important;
        }
        
        .navbar.day-mode .navbar-brand {
            color: #343a40 !important;
        }
        
        .navbar.day-mode .nav-link:hover {
            color: #0d6efd !important;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 70px; /* Account for fixed navbar */
        }
        
        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255,255,255,0.8) !important;
        }
        
        .navbar-nav .nav-link:hover {
            color: white !important;
        }
        
        .dropdown-menu {
            border: 1px solid #dee2e6;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            z-index: 9999 !important;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        /* Ensure dropdown menu shows when .show class is added */
        .dropdown-menu.show {
            display: block !important;
        }
        
        /* Dropdown positioning */
        .dropdown-menu-end {
            right: 0;
            left: auto;
        }
        
        .card {
            border: 1px solid #dee2e6;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        
        .btn {
            border-radius: 0.375rem;
        }
        
        .form-control, .form-select {
            border-radius: 0.375rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .alert {
            border-radius: 0.375rem;
        }
        
        .table {
            border-radius: 0.375rem;
        }
        
        .badge {
            border-radius: 0.375rem;
        }
        
        /* Notification dropdown styles */
        .notification-dropdown {
            border: 1px solid #dee2e6;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            border-radius: 0.5rem;
        }
        
        .notification-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f8f9fa;
            transition: background-color 0.2s ease;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        
        .notification-item.unread {
            background-color: #e3f2fd;
            border-left: 3px solid #2196f3;
        }
        
        .notification-item.unread:hover {
            background-color: #bbdefb;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        /* Reminder dropdown styles */
        .reminder-dropdown {
            border: 1px solid #dee2e6;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            border-radius: 0.5rem;
        }
        
        .reminder-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f8f9fa;
            transition: background-color 0.2s ease;
        }
        
        .reminder-item:hover {
            background-color: #f8f9fa;
        }
        
        .reminder-item.completed {
            background-color: #e3f2fd;
            border-left: 3px solid #2196f3;
        }
        
        .reminder-item.completed:hover {
            background-color: #bbdefb;
        }
        
        .reminder-item:last-child {
            border-bottom: none;
        }
        
        .dropdown-header {
            background-color: #f8f9fa;
            font-weight: 600;
            padding: 0.75rem 1rem;
        }
        
        /* Main content alignment */
        .main-content {
            width: calc(100% - 280px) !important;
            max-width: none !important;
            margin-left: 280px !important;
            padding: 2rem !important;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                width: 100% !important;
                margin-left: 0 !important;
                padding: 1rem !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" id="mainNavbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo base_url('staff/dashboard'); ?>">
                <i class="fas fa-shield-alt me-2"></i>
                USA Pharmacy 365 Staff
            </a>
            
            <!-- USA Timings Display -->
            <div class="navbar-nav mx-auto d-none d-lg-flex">
                <div class="nav-item me-3">
                    <span class="nav-link text-white">
                        <i class="fas fa-sun me-1"></i>
                        <strong>Eastern:</strong> <span id="eastern-time">--:--</span>
                    </span>
                </div>
                <div class="nav-item me-3">
                    <span class="nav-link text-white">
                        <i class="fas fa-sun me-1"></i>
                        <strong>Central:</strong> <span id="central-time">--:--</span>
                    </span>
                </div>
                <div class="nav-item me-3">
                    <span class="nav-link text-white">
                        <i class="fas fa-moon me-1"></i>
                        <strong>Mountain:</strong> <span id="mountain-time">--:--</span>
                    </span>
                </div>
                <div class="nav-item">
                    <span class="nav-link text-white">
                        <i class="fas fa-moon me-1"></i>
                        <strong>Pacific:</strong> <span id="pacific-time">--:--</span>
                    </span>
                </div>
            </div>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Notification Bell -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <?php 
                            $unread_notifications = $this->db->where('user_id', $this->session->userdata('user_id'))
                                                             ->where('user_type', 'staff')
                                                             ->where('is_read', FALSE)
                                                             ->count_all_results('notifications');
                            if ($unread_notifications > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    <?php echo $unread_notifications > 99 ? '99+' : $unread_notifications; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                            <li class="dropdown-header d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-bell me-2"></i>Notifications</span>
                                <?php if ($unread_notifications > 0): ?>
                                    <button class="btn btn-sm btn-outline-primary" onclick="markAllNotificationsRead()">
                                        <i class="fas fa-check-double me-1"></i>Mark All Read
                                    </button>
                                <?php endif; ?>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <div id="notification-list">
                                <?php 
                                $notifications = $this->db->where('user_id', $this->session->userdata('user_id'))
                                                          ->where('user_type', 'staff')
                                                          ->order_by('created_at', 'DESC')
                                                          ->limit(10)
                                                          ->get('notifications')
                                                          ->result();
                                
                                if (empty($notifications)): ?>
                                    <li class="dropdown-item text-center text-muted py-3">
                                        <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                        <div>No notifications</div>
                                    </li>
                                <?php else: ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <li class="dropdown-item notification-item <?php echo $notification->is_read ? '' : 'unread'; ?>" 
                                            data-notification-id="<?php echo $notification->id; ?>">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-<?php echo $notification->type === 'order' ? 'shopping-cart' : ($notification->type === 'ticket' ? 'ticket-alt' : 'info-circle'); ?> text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="fw-bold small"><?php echo htmlspecialchars($notification->title); ?></div>
                                                    <div class="text-muted small"><?php echo htmlspecialchars($notification->message); ?></div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">
                                                        <?php echo date('M j, g:i A', strtotime($notification->created_at)); ?>
                                                    </div>
                                                </div>
                                                <?php if (!$notification->is_read): ?>
                                                    <div class="flex-shrink-0 ms-2">
                                                        <button class="btn btn-sm btn-outline-success" onclick="markNotificationRead(<?php echo $notification->id; ?>)">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <li><hr class="dropdown-divider"></li>
                            <li class="dropdown-item text-center">
                                <a href="<?php echo base_url('staff/notifications'); ?>" class="text-decoration-none">
                                    <i class="fas fa-eye me-1"></i>View All Notifications
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Reminder Bell -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" id="reminderDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-clock"></i>
                            <?php 
                            $active_reminders = $this->db->where('status', 'active')->count_all_results('customer_reminders');
                            if ($active_reminders > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning" style="font-size: 0.6rem;">
                                    <?php echo $active_reminders > 99 ? '99+' : $active_reminders; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end reminder-dropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                            <li class="dropdown-header d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clock me-2"></i>Active Reminders</span>
                                <a href="<?php echo base_url('staff/customer_reminders'); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>View All
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <div id="reminder-list">
                                <?php 
                                $reminders = $this->db->select('customer_reminders.*, 
                                                               customer.first_name as customer_first_name, 
                                                               customer.last_name as customer_last_name')
                                                      ->from('customer_reminders')
                                                      ->join('users as customer', 'customer.id = customer_reminders.customer_id')
                                                      ->where('customer_reminders.status', 'active')
                                                      ->order_by('customer_reminders.due_date', 'ASC')
                                                      ->limit(10)
                                                      ->get()
                                                      ->result();
                                
                                if (empty($reminders)): ?>
                                    <li class="dropdown-item text-center text-muted py-3">
                                        <i class="fas fa-clock fa-2x mb-2"></i>
                                        <div>No active reminders</div>
                                    </li>
                                <?php else: ?>
                                    <?php foreach ($reminders as $reminder): ?>
                                        <li class="dropdown-item reminder-item" data-reminder-id="<?php echo $reminder->id; ?>">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-clock text-warning"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="fw-bold small"><?php echo htmlspecialchars($reminder->title); ?></div>
                                                    <div class="text-muted small"><?php echo htmlspecialchars($reminder->content); ?></div>
                                                    <?php if ($reminder->due_date): ?>
                                                        <div class="text-muted" style="font-size: 0.75rem;">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            <?php echo date('M j, Y', strtotime($reminder->due_date)); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (isset($reminder->customer_first_name) || isset($reminder->customer_last_name)): ?>
                                                        <div class="text-muted" style="font-size: 0.75rem;">
                                                            <i class="fas fa-user me-1"></i>
                                                            <?php echo htmlspecialchars(($reminder->customer_first_name ?? '') . ' ' . ($reminder->customer_last_name ?? '')); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <button class="btn btn-sm btn-outline-success" onclick="markReminderComplete(<?php echo $reminder->id; ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <li><hr class="dropdown-divider"></li>
                            <li class="dropdown-item text-center">
                                <a href="<?php echo base_url('staff/customer_reminders'); ?>" class="text-decoration-none">
                                    <i class="fas fa-plus me-1"></i>Add New Reminder
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo base_url('staff/dashboard'); ?>">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Time Update Script -->
    <script>
        function updateTimes() {
            try {
                const now = new Date();
                
                // Format time with timezone - with fallback
                const formatTime = (date, timezone) => {
                    try {
                        return date.toLocaleTimeString('en-US', {
                            timeZone: timezone,
                            hour12: true,
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                    } catch (error) {
                        console.warn('Timezone error for', timezone, error);
                        // Fallback: calculate time difference manually
                        return calculateTimeWithOffset(date, timezone);
                    }
                };
                
                // Manual time calculation as fallback
                function calculateTimeWithOffset(date, timezone) {
                    const utc = date.getTime() + (date.getTimezoneOffset() * 60000);
                    let offset = 0;
                    
                    switch(timezone) {
                        case 'America/New_York':
                            offset = -5; // EST
                            break;
                        case 'America/Chicago':
                            offset = -6; // CST
                            break;
                        case 'America/Denver':
                            offset = -7; // MST
                            break;
                        case 'America/Los_Angeles':
                            offset = -8; // PST
                            break;
                        default:
                            offset = 0;
                    }
                    
                    const targetTime = new Date(utc + (offset * 3600000));
                    return targetTime.toLocaleTimeString('en-US', {
                        hour12: true,
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                }
                
                // Update USA time zones with error handling
                const easternElement = document.getElementById('eastern-time');
                const centralElement = document.getElementById('central-time');
                const mountainElement = document.getElementById('mountain-time');
                const pacificElement = document.getElementById('pacific-time');
                
                if (easternElement) {
                    easternElement.textContent = formatTime(now, 'America/New_York');
                }
                if (centralElement) {
                    centralElement.textContent = formatTime(now, 'America/Chicago');
                }
                if (mountainElement) {
                    mountainElement.textContent = formatTime(now, 'America/Denver');
                }
                if (pacificElement) {
                    pacificElement.textContent = formatTime(now, 'America/Los_Angeles');
                }
                
                // Update day/night mode based on Eastern time
                updateDayNightMode(now);
                
            } catch (error) {
                console.error('Error updating times:', error);
                // Set fallback times if everything fails
                const elements = ['eastern-time', 'central-time', 'mountain-time', 'pacific-time'];
                elements.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = new Date().toLocaleTimeString('en-US', {
                            hour12: true,
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                    }
                });
            }
        }
        
        function updateDayNightMode(date) {
            try {
                const navbar = document.getElementById('mainNavbar');
                if (!navbar) return;
                
                const hour = date.getHours();
                
                // Day mode: 6 AM to 6 PM (6:00 - 17:59)
                // Night mode: 6 PM to 6 AM (18:00 - 5:59)
                if (hour >= 6 && hour < 18) {
                    // Day mode
                    navbar.classList.remove('night-mode');
                    navbar.classList.add('day-mode');
                } else {
                    // Night mode
                    navbar.classList.remove('day-mode');
                    navbar.classList.add('night-mode');
                }
            } catch (error) {
                console.error('Error updating day/night mode:', error);
            }
        }
        
        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            // Update times immediately and then every second
            updateTimes();
            setInterval(updateTimes, 1000);
        });
        
        // Also try to update immediately in case DOM is already ready
        if (document.readyState === 'loading') {
            // DOM is still loading, wait for DOMContentLoaded
        } else {
            // DOM is already ready, update immediately
            updateTimes();
            setInterval(updateTimes, 1000);
        }
        
        // Initialize dropdowns manually if Bootstrap is not loaded
        function initializeDropdowns() {
            // Check if Bootstrap is available
            if (typeof bootstrap !== 'undefined') {
                // Initialize all dropdowns
                var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });
            } else {
                // Fallback: Simple dropdown toggle
                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('dropdown-toggle') || e.target.closest('.dropdown-toggle')) {
                        e.preventDefault();
                        const dropdown = e.target.closest('.dropdown');
                        const menu = dropdown.querySelector('.dropdown-menu');
                        
                        // Close all other dropdowns
                        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                            menu.classList.remove('show');
                        });
                        
                        // Toggle current dropdown
                        menu.classList.toggle('show');
                    } else {
                        // Close dropdowns when clicking outside
                        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                            menu.classList.remove('show');
                        });
                    }
                });
            }
        }
        
        // Initialize dropdowns when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeDropdowns();
        });
        
        // Also try to initialize immediately if DOM is already ready
        if (document.readyState !== 'loading') {
            initializeDropdowns();
        }
        
        // Notification management functions
        function markNotificationRead(notificationId) {
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
                    // Update the notification item
                    const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    if (notificationItem) {
                        notificationItem.classList.remove('unread');
                        const markReadBtn = notificationItem.querySelector('.btn-outline-success');
                        if (markReadBtn) {
                            markReadBtn.remove();
                        }
                    }
                    
                    // Update notification count
                    updateNotificationCount();
                } else {
                    console.error('Error marking notification as read:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        
        function markAllNotificationsRead() {
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
                    // Remove unread styling from all notifications
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread');
                        const markReadBtn = item.querySelector('.btn-outline-success');
                        if (markReadBtn) {
                            markReadBtn.remove();
                        }
                    });
                    
                    // Update notification count
                    updateNotificationCount();
                    
                    // Hide the "Mark All Read" button
                    const markAllBtn = document.querySelector('.dropdown-header .btn-outline-primary');
                    if (markAllBtn) {
                        markAllBtn.style.display = 'none';
                    }
                } else {
                    console.error('Error marking all notifications as read:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        
        function updateNotificationCount() {
            // Count remaining unread notifications
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const badge = document.querySelector('#notificationDropdown .badge');
            
            if (unreadCount === 0) {
                if (badge) {
                    badge.style.display = 'none';
                }
            } else {
                if (badge) {
                    badge.style.display = 'block';
                    badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                }
            }
        }
        
        // Reminder management functions
        function markReminderComplete(reminderId) {
            fetch('<?php echo base_url('staff/mark_reminder_complete'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'reminder_id=' + reminderId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the reminder item
                    const reminderItem = document.querySelector(`[data-reminder-id="${reminderId}"]`);
                    if (reminderItem) {
                        reminderItem.classList.add('completed');
                        const markCompleteBtn = reminderItem.querySelector('.btn-outline-success');
                        if (markCompleteBtn) {
                            markCompleteBtn.innerHTML = '<i class="fas fa-check-double"></i>';
                            markCompleteBtn.classList.remove('btn-outline-success');
                            markCompleteBtn.classList.add('btn-success');
                        }
                    }
                    
                    // Update reminder count
                    updateReminderCount();
                } else {
                    console.error('Error marking reminder as complete:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        
        function updateReminderCount() {
            // Count remaining active reminders
            const activeCount = document.querySelectorAll('.reminder-item:not(.completed)').length;
            const badge = document.querySelector('#reminderDropdown .badge');
            
            if (activeCount === 0) {
                if (badge) {
                    badge.style.display = 'none';
                }
            } else {
                if (badge) {
                    badge.style.display = 'block';
                    badge.textContent = activeCount > 99 ? '99+' : activeCount;
                }
            }
        }
        
        // Auto-refresh notifications every 30 seconds
        setInterval(function() {
            fetch('<?php echo base_url('staff/get_notifications'); ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.notifications) {
                    // Update notification list if there are new notifications
                    const currentCount = document.querySelectorAll('.notification-item').length;
                    if (data.notifications.length !== currentCount) {
                        location.reload(); // Simple refresh for now
                    }
                }
            })
            .catch(error => {
                console.error('Error refreshing notifications:', error);
            });
        }, 30000);
        
        // Auto-refresh reminders every 60 seconds
        setInterval(function() {
            fetch('<?php echo base_url('staff/get_reminders'); ?>', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.reminders) {
                    // Update reminder list if there are changes
                    const currentCount = document.querySelectorAll('.reminder-item').length;
                    if (data.reminders.length !== currentCount) {
                        location.reload(); // Simple refresh for now
                    }
                }
            })
            .catch(error => {
                console.error('Error refreshing reminders:', error);
            });
        }, 60000);
    </script> 