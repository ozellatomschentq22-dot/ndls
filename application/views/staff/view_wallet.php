<?php $this->load->view('staff/includes/header'); ?>

<style>
    .main-content {
        width: calc(100% - 280px) !important;
        max-width: none !important;
        margin-left: 280px !important;
    }
    
    .card {
        width: 100% !important;
        max-width: none !important;
    }
    
    .table-responsive {
        width: 100% !important;
    }
    
    .table {
        width: 100% !important;
    }
    
    @media (max-width: 768px) {
        .main-content {
            width: 100% !important;
            margin-left: 0 !important;
        }
    }
</style>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-wallet me-2 text-success"></i>Customer Wallet Details
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/wallets'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Wallets
                    </a>
                </div>
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

        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Customer Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="mb-2">
                            <label class="form-label fw-bold">Customer Name</label>
                            <p class="mb-0"><?php echo $wallet->first_name . ' ' . $wallet->last_name; ?></p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">Email</label>
                            <p class="mb-0"><?php echo $wallet->email; ?></p>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold">Username</label>
                            <p class="mb-0"><?php echo $wallet->username; ?></p>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="mb-2">
                            <label class="form-label fw-bold">Current Balance</label>
                            <p class="mb-0 text-primary fs-3">$<?php echo number_format($wallet->balance, 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-list fa-2x text-primary mb-2"></i>
                        <h4 class="mb-1"><?php echo count($transactions); ?></h4>
                        <p class="text-muted mb-0">Total Transactions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-plus-circle fa-2x text-success mb-2"></i>
                        <h4 class="mb-1"><?php echo count(array_filter($transactions, function($t) { return $t->type === 'credit'; })); ?></h4>
                        <p class="text-muted mb-0">Credits</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <i class="fas fa-minus-circle fa-2x text-danger mb-2"></i>
                        <h4 class="mb-1"><?php echo count(array_filter($transactions, function($t) { return $t->type === 'debit'; })); ?></h4>
                        <p class="text-muted mb-0">Debits</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-dollar-sign fa-2x text-info mb-2"></i>
                        <h4 class="mb-1">$<?php echo number_format(array_sum(array_map(function($t) { return $t->type === 'credit' ? $t->amount : -$t->amount; }, $transactions)), 2); ?></h4>
                        <p class="text-muted mb-0">Net Change</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Transaction History
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($transactions)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Balance After</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td>#<?php echo $transaction->id; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $transaction->type === 'credit' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($transaction->type); ?>
                                        </span>
                                    </td>
                                    <td class="text-<?php echo $transaction->type === 'credit' ? 'success' : 'danger'; ?> fw-bold">
                                        <?php echo $transaction->type === 'credit' ? '+' : '-'; ?>$<?php echo number_format($transaction->amount, 2); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($transaction->description); ?></td>
                                    <td><?php echo date('M j, Y H:i', strtotime($transaction->created_at)); ?></td>
                                    <td class="fw-bold">$<?php echo number_format($transaction->balance_after, 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No transactions found</h5>
                        <p class="text-muted">This customer hasn't made any wallet transactions yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('staff/includes/footer'); ?> 