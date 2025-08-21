<?php
$this->load->view('customer/common/header');
?>

<style>
    .dashboard-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: none;
        border-radius: 12px;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-card.wallet {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stat-card.wallet.negative {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    }
    
    .stat-card.orders {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-card.support {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }
    
    .stat-card.addresses {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .quick-action-btn {
        border-radius: 10px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .welcome-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .missing-info-alert {
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </h1>
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

<!-- Welcome Section -->
<div class="welcome-section">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h2 class="mb-2 fw-bold">Welcome back, <?php echo htmlspecialchars($user->first_name); ?>! 👋</h2>
            <p class="mb-0 opacity-75">Here's your account overview and quick access to everything you need.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <div class="d-flex gap-2 justify-content-lg-end flex-wrap">
                <a href="<?php echo base_url('customer/products'); ?>" class="btn btn-light quick-action-btn">
                    <i class="fas fa-shopping-cart me-2"></i>Shop Now
                </a>
                <a href="<?php echo base_url('customer/wallet'); ?>" class="btn btn-outline-light quick-action-btn">
                    <i class="fas fa-wallet me-2"></i>My Wallet
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Missing Information Warning -->
<?php if (!empty($missing_info)): ?>
    <div class="alert missing-info-alert alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-start">
            <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-2 fw-bold">Complete Your Profile</h5>
                <p class="mb-3">Please add the following required information to continue:</p>
                <div class="row g-3">
                    <?php if (in_array('phone', $missing_info)): ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-white bg-opacity-50 rounded">
                                <i class="fas fa-phone text-warning me-3 fa-lg"></i>
                                <div>
                                    <strong>Phone Number</strong><br>
                                    <small class="text-muted">Required for order notifications</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (in_array('address', $missing_info)): ?>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-white bg-opacity-50 rounded">
                                <i class="fas fa-map-marker-alt text-warning me-3 fa-lg"></i>
                                <div>
                                    <strong>Shipping Address</strong><br>
                                    <small class="text-muted">Required for order delivery</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <?php if (in_array('phone', $missing_info)): ?>
                        <a href="<?php echo base_url('customer/profile'); ?>" class="btn btn-warning quick-action-btn">
                            <i class="fas fa-phone me-2"></i>Add Phone Number
                        </a>
                    <?php endif; ?>
                    <?php if (in_array('address', $missing_info)): ?>
                        <a href="<?php echo base_url('customer/add_address'); ?>" class="btn btn-warning quick-action-btn">
                            <i class="fas fa-map-marker-alt me-2"></i>Add Address
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <!-- Wallet Balance -->
    <div class="col-12">
        <div class="stat-card wallet <?php echo ($wallet_balance < 0) ? 'negative' : ''; ?>" style="padding: 1rem;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1 opacity-75">Wallet Balance</h6>
                    <h3 class="mb-1 fw-bold">
                        $<?php echo number_format($wallet_balance, 2); ?>
                    </h3>
                    <small class="opacity-75">
                        <?php if ($wallet_balance < 0): ?>
                            <i class="fas fa-exclamation-circle me-1"></i>Payment Required
                        <?php else: ?>
                            <i class="fas fa-check-circle me-1"></i>Available for purchases
                        <?php endif; ?>
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo base_url('customer/recharge'); ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-plus-circle me-1"></i>Recharge
                    </a>
                    <a href="<?php echo base_url('customer/wallet'); ?>" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-history me-1"></i>History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo base_url('customer/products'); ?>" class="btn btn-outline-primary w-100 quick-action-btn">
                            <i class="fas fa-pills fa-2x mb-2"></i><br>
                            <strong>Browse Products</strong><br>
                            <small class="text-muted">View all available products</small>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo base_url('customer/orders'); ?>" class="btn btn-outline-success w-100 quick-action-btn">
                            <i class="fas fa-shopping-bag fa-2x mb-2"></i><br>
                            <strong>My Orders</strong><br>
                            <small class="text-muted">Track your order history</small>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo base_url('customer/support_tickets'); ?>" class="btn btn-outline-warning w-100 quick-action-btn">
                            <i class="fas fa-headset fa-2x mb-2"></i><br>
                            <strong>Get Support</strong><br>
                            <small class="text-muted">Contact customer support</small>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo base_url('customer/profile'); ?>" class="btn btn-outline-info w-100 quick-action-btn">
                            <i class="fas fa-user-cog fa-2x mb-2"></i><br>
                            <strong>My Profile</strong><br>
                            <small class="text-muted">Update your information</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Create Links -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card dashboard-card">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-plus-circle me-2 text-success"></i>Quick Create
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo base_url('customer/create_ticket'); ?>" class="btn btn-outline-danger w-100 quick-action-btn">
                            <i class="fas fa-ticket-alt fa-2x mb-2"></i><br>
                            <strong>Create Ticket</strong><br>
                            <small class="text-muted">Submit a support ticket</small>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo base_url('customer/add_address'); ?>" class="btn btn-outline-secondary w-100 quick-action-btn">
                            <i class="fas fa-map-marker-alt fa-2x mb-2"></i><br>
                            <strong>Add Address</strong><br>
                            <small class="text-muted">Add new shipping address</small>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo base_url('customer/recharge'); ?>" class="btn btn-outline-warning w-100 quick-action-btn">
                            <i class="fas fa-credit-card fa-2x mb-2"></i><br>
                            <strong>Request Recharge</strong><br>
                            <small class="text-muted">Add funds to your wallet</small>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="<?php echo base_url('customer/chat_with_admin'); ?>" class="btn btn-outline-dark w-100 quick-action-btn">
                            <i class="fas fa-comments fa-2x mb-2"></i><br>
                            <strong>Chat with Admin</strong><br>
                            <small class="text-muted">Send a message to admin</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('customer/common/footer'); ?> 