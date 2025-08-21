<?php
// Get current page for active state
$current_page = $this->uri->segment(2) ?: 'dashboard';
$user_role = $this->session->userdata('role');
$is_admin = ($user_role === 'admin');
$is_staff = ($user_role === 'staff');
?>
<div class="sidebar bg-dark text-white" style="width: 280px; position: fixed; left: 0; top: 0; height: 100vh; z-index: 1000; overflow-y: auto;">
    <div class="p-3 border-bottom border-secondary">
        <h5 class="mb-0">
            <i class="fas fa-shield-alt me-2"></i>
            <?php echo $is_admin ? 'Admin Panel' : 'Staff Panel'; ?>
        </h5>
    </div>
    
    <nav class="nav flex-column p-3">
        <!-- Dashboard -->
        <li class="nav-item mb-3">
            <a class="nav-link text-white <?php echo $current_page === 'dashboard' ? 'bg-primary' : ''; ?>" 
               href="<?php echo base_url($user_role . '/dashboard'); ?>">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard
            </a>
        </li>
        
        <!-- User Management Section (Admin Only) -->
        <?php if ($is_admin): ?>
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-users me-1"></i>User Management
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $this->uri->segment(2) == 'users' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/users'); ?>">
                    <i class="fas fa-users-cog me-2"></i>
                    Admin & Staff
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $this->uri->segment(2) == 'customers' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/customers'); ?>">
                    <i class="fas fa-users me-2"></i>
                    Customers
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'leads' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/leads'); ?>">
                    <i class="fas fa-user-plus me-2"></i>
                    Leads
                    <?php 
                    $new_leads = $this->db->where('status', 'new')->count_all_results('leads');
                    if ($new_leads > 0): ?>
                        <span class="badge bg-warning ms-2"><?php echo $new_leads; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'converted_leads' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/converted_leads'); ?>">
                    <i class="fas fa-user-check me-2"></i>
                    Converted Leads
                    <?php 
                    $converted_leads = $this->db->where('status', 'converted')->count_all_results('leads');
                    if ($converted_leads > 0): ?>
                        <span class="badge bg-success ms-2"><?php echo $converted_leads; ?></span>
                    <?php endif; ?>
                </a>
            </li>
        </div>
        <?php endif; ?>
        
        <!-- Customer Management Section (Staff Only) -->
        <?php if ($is_staff): ?>
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-users me-1"></i>Customer Management
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'customers' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('staff/customers'); ?>">
                    <i class="fas fa-users me-2"></i>
                    Customers
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'leads' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('staff/leads'); ?>">
                    <i class="fas fa-user-plus me-2"></i>
                    Leads
                    <?php if (isset($new_leads) && $new_leads > 0): ?>
                        <span class="badge bg-warning ms-2"><?php echo $new_leads; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'converted_leads' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('staff/converted_leads'); ?>">
                    <i class="fas fa-user-check me-2"></i>
                    Converted Leads
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'customer_reminders' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('staff/customer_reminders'); ?>">
                    <i class="fas fa-bell me-2"></i>
                    Customer Reminders
                    <?php if (isset($active_reminders) && $active_reminders > 0): ?>
                        <span class="badge bg-warning ms-2"><?php echo $active_reminders; ?></span>
                    <?php endif; ?>
                </a>
            </li>
        </div>
        <?php endif; ?>
        
        <!-- Product Management Section (Admin Only) -->
        <?php if ($is_admin): ?>
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-box me-1"></i>Product Management
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'products' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/products'); ?>">
                    <i class="fas fa-box me-2"></i>
                    Products
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $this->uri->segment(2) == 'customer_pricing' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/customer_pricing'); ?>">
                    <i class="fas fa-tags me-2"></i>
                    Customer Pricing
                </a>
            </li>
        </div>
        <?php endif; ?>
        
        <!-- Order Management Section -->
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-shopping-cart me-1"></i>Order Management
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'orders' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url($user_role . '/orders'); ?>">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Orders
                </a>
            </li>
            
            <?php if ($is_admin): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'create_order' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/create_order'); ?>">
                    <i class="fas fa-plus-circle me-2"></i>
                    Create Order
                </a>
            </li>
            <?php endif; ?>
        </div>
        
        <!-- Drop Shipment Section -->
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-shipping-fast me-1"></i>Drop Shipment
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'dropshipment' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('dropshipment'); ?>">
                    <i class="fas fa-truck me-2"></i>
                    Orders
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'centers' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('centers'); ?>">
                    <i class="fas fa-building me-2"></i>
                    Centers
                </a>
            </li>
            
            <?php if ($is_admin): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'center_payments' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('center_payments'); ?>">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Center Payments
                </a>
            </li>
            <?php endif; ?>
        </div>
        
        <!-- Financial Management Section -->
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-dollar-sign me-1"></i>Financial Management
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'wallets' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url($user_role . '/wallets'); ?>">
                    <i class="fas fa-wallet me-2"></i>
                    Customer Wallets
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'recharge_requests' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url($user_role . '/recharge_requests'); ?>">
                    <i class="fas fa-credit-card me-2"></i>
                    Recharge Requests
                    <?php if (isset($pending_recharge_requests) && $pending_recharge_requests > 0): ?>
                        <span class="badge bg-warning ms-2"><?php echo $pending_recharge_requests; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'vendor_payments' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('vendor_payments'); ?>">
                    <i class="fas fa-handshake me-2"></i>
                    Vendor Payments
                </a>
            </li>
        </div>
        
        <!-- Vendor Management Section (Admin Only) -->
        <?php if ($is_admin): ?>
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-handshake me-1"></i>Vendor Management
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'vendors' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('vendors'); ?>">
                    <i class="fas fa-users-cog me-2"></i>
                    Vendors
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'vendor_payments' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('vendor_payments'); ?>">
                    <i class="fas fa-handshake me-2"></i>
                    Vendor Payments
                </a>
            </li>
        </div>
        <?php endif; ?>
        
        <!-- Support & Communication Section -->
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-headset me-1"></i>Support & Communication
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'tickets' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url($user_role . '/tickets'); ?>">
                    <i class="fas fa-headset me-2"></i>
                    Support Tickets
                    <?php if (isset($pending_tickets) && $pending_tickets > 0): ?>
                        <span class="badge bg-warning ms-2"><?php echo $pending_tickets; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            
            <?php if ($is_staff): ?>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'admin_messages' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('staff/admin_messages'); ?>">
                    <i class="fas fa-comments me-2"></i>
                    Admin Messages
                    <?php if (isset($unread_messages) && $unread_messages > 0): ?>
                        <span class="badge bg-warning ms-2"><?php echo $unread_messages; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endif; ?>
        </div>
        
        <!-- Logout Button -->
        <div class="mt-auto pt-3 border-top border-secondary">
            <a class="nav-link text-white" href="<?php echo base_url('auth/logout'); ?>">
                <i class="fas fa-sign-out-alt me-2"></i>
                Logout
            </a>
        </div>
    </nav>
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

/* Sidebar section styling */
.sidebar-section h6 {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.08);
    border-left: 3px solid #007bff;
}

.sidebar-section .nav-item {
    margin-bottom: 0.25rem;
}

.sidebar-section .nav-item:last-child {
    margin-bottom: 0;
}

/* Nav link hover effects */
.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
    transition: background-color 0.2s ease;
    border-radius: 6px;
}

.nav-link.active {
    border-radius: 6px;
}

/* Badge positioning */
.nav-link .badge {
    margin-left: auto;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
        width: 100%;
    }
}
</style>
