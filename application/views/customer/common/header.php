<?php
// Set default active page if not defined
if (!isset($active_page)) {
    $active_page = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - USA Pharmacy 365</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Customer Admin Styles -->
    <style>
        .navbar {
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
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
            color: rgba(255,255,255,0.9) !important;
        }
        
        .navbar-nav .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
        }
        
        .dropdown-menu {
            border: 1px solid #dee2e6;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        
        .dropdown-item:hover {
            background-color: #e7f1ff;
            color: #0d6efd;
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
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo base_url('customer/dashboard'); ?>">
                <i class="fas fa-pills me-2"></i>
                USA Pharmacy 365
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo $this->session->userdata('first_name') . ' ' . $this->session->userdata('last_name'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo base_url('customer/dashboard'); ?>">
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

    <!-- Negative Balance Popup Modal -->
    <?php if (isset($has_negative_balance) && $has_negative_balance): ?>
    <div class="modal fade" id="negativeBalanceModal" tabindex="-1" aria-labelledby="negativeBalanceModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="negativeBalanceModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Payment Required
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-wallet fa-3x text-danger mb-3"></i>
                        <h4 class="text-danger">Outstanding Balance</h4>
                        <p class="lead">You have an outstanding balance of:</p>
                        <h2 class="text-danger fw-bold">$<?php echo number_format($negative_balance_amount, 2); ?></h2>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Important:</strong> Please clear your outstanding balance to continue using our services.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-credit-card fa-2x text-primary mb-2"></i>
                                    <h6>Request Recharge</h6>
                                    <p class="small text-muted">Add funds to your wallet</p>
                                    <a href="<?php echo base_url('customer/recharge'); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus-circle me-1"></i>Recharge Now
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-history fa-2x text-info mb-2"></i>
                                    <h6>View Transactions</h6>
                                    <p class="small text-muted">Check your wallet history</p>
                                    <a href="<?php echo base_url('customer/wallet'); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-wallet me-1"></i>View Wallet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                    <a href="<?php echo base_url('customer/recharge'); ?>" class="btn btn-danger">
                        <i class="fas fa-credit-card me-1"></i>Pay Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show negative balance modal automatically
        var negativeBalanceModal = new bootstrap.Modal(document.getElementById('negativeBalanceModal'));
        negativeBalanceModal.show();
    });
    </script>
    <?php endif; ?>

    <div class="d-flex">
        <?php $this->load->view('customer/common/sidebar'); ?>
        
        <div class="main-content"> 