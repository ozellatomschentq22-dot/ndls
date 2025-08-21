<?php
// Get current page for active state
$current_page = $this->uri->segment(2) ?: 'dashboard';
?>
<div class="sidebar bg-primary text-white" style="width: 280px; position: fixed; left: 0; top: 0; height: 100vh; z-index: 1000; overflow-y: auto; background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
    <div class="p-3 border-bottom border-light border-opacity-25">
        <h5 class="mb-0">
            <i class="fas fa-pills me-2"></i>
            Pharmacy Panel
        </h5>
    </div>
    
    <nav class="nav flex-column p-3">
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'dashboard' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/dashboard'); ?>">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'products' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/products'); ?>">
                <i class="fas fa-pills me-2"></i>
                Products
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'orders' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/orders'); ?>">
                <i class="fas fa-shopping-bag me-2"></i>
                My Orders
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'wallet' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/wallet'); ?>">
                <i class="fas fa-wallet me-2"></i>
                My Wallet
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'recharge' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/recharge'); ?>">
                <i class="fas fa-plus-circle me-2"></i>
                Request Recharge
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'support_tickets' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/support_tickets'); ?>">
                <i class="fas fa-ticket-alt me-2"></i>
                Support Tickets
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'chat_with_admin' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/chat_with_admin'); ?>">
                <i class="fas fa-comments me-2"></i>
                Chat with Admin
                <?php 
                $unread_messages = $this->db->where('customer_id', $this->session->userdata('user_id'))
                                            ->where('is_read', FALSE)
                                            ->where('is_admin_reply', TRUE)
                                            ->count_all_results('admin_messages');
                if ($unread_messages > 0): ?>
                    <span class="badge bg-danger ms-2"><?php echo $unread_messages; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'notifications' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/notifications'); ?>">
                <i class="fas fa-bell me-2"></i>
                Notifications
                <?php 
                $unread_notifications = $this->db->where('user_id', $this->session->userdata('user_id'))
                                                 ->where('user_type', 'customer')
                                                 ->where('is_read', FALSE)
                                                 ->count_all_results('notifications');
                if ($unread_notifications > 0): ?>
                    <span class="badge bg-danger ms-2"><?php echo $unread_notifications; ?></span>
                <?php endif; ?>
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'profile' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/profile'); ?>">
                <i class="fas fa-user-cog me-2"></i>
                Profile Settings
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link text-white <?php echo $current_page === 'addresses' ? 'bg-primary bg-opacity-75' : 'hover-bg-primary'; ?>" 
               href="<?php echo base_url('customer/addresses'); ?>">
                <i class="fas fa-map-marker-alt me-2"></i>
                My Addresses
            </a>
        </li>
    </nav>
    
    <div class="p-3 border-top border-light border-opacity-25" style="position: absolute; bottom: 0; left: 0; right: 0;">
        <div class="d-flex align-items-center mb-3">
            <div class="me-3">
                <i class="fas fa-user-md fa-2x text-light text-opacity-75"></i>
            </div>
            <div>
                <div class="fw-bold"><?php echo $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'); ?></div>
                <div class="text-light text-opacity-75 small">Pharmacy Customer</div>
            </div>
        </div>
        <a href="<?php echo base_url('auth/logout'); ?>" class="btn btn-outline-light btn-sm w-100">
            <i class="fas fa-sign-out-alt me-2"></i>
            Logout
        </a>
    </div>
</div>

<style>
/* Main content adjustment */
.main-content {
    margin-left: 280px;
    padding: 2rem;
    min-height: 100vh;
    background: #f8f9fa;
    width: calc(100% - 280px);
    max-width: none;
}

/* Hover effects for nav links */
.sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
    transition: background-color 0.3s ease;
}

/* Active state styling */
.sidebar .nav-link.bg-primary.bg-opacity-75 {
    background-color: rgba(255, 255, 255, 0.2) !important;
    border-radius: 8px;
    font-weight: 600;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .sidebar {
        width: 100% !important;
        position: relative !important;
        height: auto !important;
    }
    
    .main-content {
        margin-left: 0;
        padding: 1rem;
        width: 100%;
    }
}
</style> 