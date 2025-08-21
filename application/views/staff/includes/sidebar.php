<style>
    .sidebar {
        width: 280px;
        background: linear-gradient(180deg, #28a745 0%, #20c997 100%);
        min-height: calc(100vh - 70px);
        position: fixed;
        top: 70px;
        left: 0;
        z-index: 1000;
        transition: all 0.3s ease;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    
    .sidebar-nav {
        padding: 1rem 0;
    }
    
    .sidebar-nav .nav-link {
        color: rgba(255,255,255,0.9);
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .sidebar-nav .nav-link:hover {
        color: white;
        background-color: rgba(255,255,255,0.1);
        border-left-color: rgba(255,255,255,0.5);
    }
    
    .sidebar-nav .nav-link.active {
        color: white;
        background-color: rgba(255,255,255,0.2);
        border-left-color: white;
    }
    
    .sidebar-nav .nav-link i {
        width: 20px;
        margin-right: 0.75rem;
    }
    
    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 1rem;
    }
    
    .sidebar-header h5 {
        color: white;
        margin: 0;
        font-weight: 600;
    }
    
    .sidebar-header p {
        color: rgba(255,255,255,0.8);
        margin: 0;
        font-size: 0.875rem;
    }
    
    .badge-count {
        background-color: rgba(255,255,255,0.2);
        color: white;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 10px;
        margin-left: auto;
    }
    
    .sidebar-section {
        margin-bottom: 1.5rem;
    }
    
    .sidebar-section-title {
        color: rgba(255,255,255,0.7);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.5rem 1.5rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid rgba(255,255,255,0.3);
    }
    
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
    }
</style>

<div class="sidebar" style="width: 280px; position: fixed; left: 0; top: 70px; height: calc(100vh - 70px); z-index: 1000; overflow-y: auto;">
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <a class="nav-link <?php echo $active_page == 'dashboard' ? 'active' : ''; ?>" href="<?php echo base_url('staff/dashboard'); ?>">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        
        <!-- Customer Management Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">
                <i class="fas fa-users me-1"></i>Customer Management
            </div>
            
            <a class="nav-link <?php echo $active_page == 'customers' ? 'active' : ''; ?>" href="<?php echo base_url('staff/customers'); ?>">
                <i class="fas fa-users"></i>
                Customers
            </a>
            
            <a class="nav-link <?php echo $active_page == 'leads' ? 'active' : ''; ?>" href="<?php echo base_url('staff/leads'); ?>">
                <i class="fas fa-user-plus"></i>
                Leads
                <?php if (isset($new_leads) && $new_leads > 0): ?>
                    <span class="badge-count"><?php echo $new_leads; ?></span>
                <?php endif; ?>
            </a>
            
            <a class="nav-link <?php echo $active_page == 'converted_leads' ? 'active' : ''; ?>" href="<?php echo base_url('staff/converted_leads'); ?>">
                <i class="fas fa-user-check"></i>
                Converted Leads
            </a>
            
            <a class="nav-link <?php echo $active_page == 'customer_reminders' ? 'active' : ''; ?>" href="<?php echo base_url('staff/customer_reminders'); ?>">
                <i class="fas fa-bell"></i>
                Customer Reminders
                <?php if (isset($active_reminders) && $active_reminders > 0): ?>
                    <span class="badge-count"><?php echo $active_reminders; ?></span>
                <?php endif; ?>
            </a>
        </div>
        
        <!-- Order Management Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">
                <i class="fas fa-shopping-cart me-1"></i>Order Management
            </div>
            
            <a class="nav-link <?php echo $active_page == 'orders' ? 'active' : ''; ?>" href="<?php echo base_url('staff/orders'); ?>">
                <i class="fas fa-shopping-bag"></i>
                Orders
            </a>
        </div>
        
        <!-- Drop Shipment Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">
                <i class="fas fa-shipping-fast me-1"></i>Drop Shipment
            </div>
            
            <a class="nav-link <?php echo $active_page == 'dropshipment' ? 'active' : ''; ?>" href="<?php echo base_url('dropshipment'); ?>">
                <i class="fas fa-truck"></i>
                Orders
            </a>
        </div>
        
        <!-- Financial Management Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">
                <i class="fas fa-dollar-sign me-1"></i>Financial Management
            </div>
            
            <a class="nav-link <?php echo $active_page == 'wallets' ? 'active' : ''; ?>" href="<?php echo base_url('staff/wallets'); ?>">
                <i class="fas fa-wallet"></i>
                Customer Wallets
            </a>
            
            <a class="nav-link <?php echo $active_page == 'recharge_requests' ? 'active' : ''; ?>" href="<?php echo base_url('staff/recharge_requests'); ?>">
                <i class="fas fa-credit-card"></i>
                Recharge Requests
                <?php if (isset($pending_recharge_requests) && $pending_recharge_requests > 0): ?>
                    <span class="badge-count"><?php echo $pending_recharge_requests; ?></span>
                <?php endif; ?>
            </a>
            
            <a class="nav-link <?php echo $active_page == 'vendor_payments' ? 'active' : ''; ?>" href="<?php echo base_url('vendor_payments'); ?>">
                <i class="fas fa-handshake"></i>
                Vendor Payments
            </a>
        </div>
        
        <!-- Support & Communication Section -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">
                <i class="fas fa-headset me-1"></i>Support & Communication
            </div>
            
            <a class="nav-link <?php echo $active_page == 'tickets' ? 'active' : ''; ?>" href="<?php echo base_url('staff/tickets'); ?>">
                <i class="fas fa-headset"></i>
                Support Tickets
                <?php if (isset($pending_tickets) && $pending_tickets > 0): ?>
                    <span class="badge-count"><?php echo $pending_tickets; ?></span>
                <?php endif; ?>
            </a>
            
            <a class="nav-link <?php echo $active_page == 'admin_messages' ? 'active' : ''; ?>" href="<?php echo base_url('staff/admin_messages'); ?>">
                <i class="fas fa-comments"></i>
                Admin Messages
                <?php if (isset($unread_messages) && $unread_messages > 0): ?>
                    <span class="badge-count"><?php echo $unread_messages; ?></span>
                <?php endif; ?>
            </a>
        </div>
        
        <!-- Logout Button -->
        <div style="margin-top: 2rem; padding: 0 1.5rem;">
            <a class="nav-link" href="<?php echo base_url('auth/logout'); ?>" style="background-color: #dc3545; color: white; border-radius: 5px; text-align: center; margin-bottom: 1rem;">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </nav>
</div> 