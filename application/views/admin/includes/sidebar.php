<?php
// Get current page for active state
$current_page = $this->uri->segment(2) ?: 'dashboard';
?>
<div class="sidebar bg-dark text-white" style="width: 280px; position: fixed; left: 0; top: 0; height: 100vh; z-index: 1000; overflow-y: auto;">
    <div class="p-3 border-bottom border-secondary">
        <h5 class="mb-0">
            <i class="fas fa-shield-alt me-2"></i>
            Admin Panel
        </h5>
    </div>
    
    <nav class="nav flex-column p-3">
        <!-- Dashboard -->
        <li class="nav-item mb-3">
            <a class="nav-link text-white <?php echo $current_page === 'dashboard' ? 'bg-primary' : ''; ?>" 
               href="<?php echo base_url('admin/dashboard'); ?>">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard
            </a>
        </li>
        
        <!-- User Management Section -->
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
        
        <!-- Product Management Section -->
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
        
        <!-- Order Management Section -->
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-shopping-cart me-1"></i>Order Management
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'orders' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/orders'); ?>">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Orders
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'create_order' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/create_order'); ?>">
                    <i class="fas fa-plus-circle me-2"></i>
                    Create Order
                </a>
            </li>
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
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'center_payments' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('center_payments'); ?>">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Center Payments
                </a>
            </li>
        </div>
        
        <!-- Financial Management Section -->
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-dollar-sign me-1"></i>Financial Management
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'wallets' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/wallets'); ?>">
                    <i class="fas fa-wallet me-2"></i>
                    Customer Wallets
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'payment_methods' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/payment_methods'); ?>">
                    <i class="fas fa-credit-card me-2"></i>
                    Payment Methods
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'recharge_requests' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/recharge_requests'); ?>">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Recharge Requests
                </a>
            </li>
        </div>
        
        <!-- Vendor Management Section -->
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
        
        <!-- Support & Communication Section -->
        <div class="sidebar-section mb-4">
            <h6 class="text-muted text-uppercase small mb-3 px-2">
                <i class="fas fa-headset me-1"></i>Support & Communication
            </h6>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'tickets' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/tickets'); ?>">
                    <i class="fas fa-ticket-alt me-2"></i>
                    Support Tickets
                    <span class="badge bg-danger ms-auto" id="ticket-notification" style="display: none;">New</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_page === 'admin_messages' ? 'bg-primary' : ''; ?>" 
                   href="<?php echo base_url('admin/admin_messages'); ?>">
                    <i class="fas fa-comments me-2"></i>
                    Admin Messages
                    <?php 
                    $unread_messages = $this->db->where('is_read', FALSE)
                                                ->where('is_admin_reply', FALSE)
                                                ->count_all_results('admin_messages');
                    if ($unread_messages > 0): ?>
                        <span class="badge bg-danger ms-2"><?php echo $unread_messages; ?></span>
                    <?php endif; ?>
                </a>
            </li>
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
    float: right;
    margin-top: 2px;
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