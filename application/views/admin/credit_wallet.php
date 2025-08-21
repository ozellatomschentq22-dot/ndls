<?php $this->load->view('admin/includes/header'); ?>

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
    
    @media (max-width: 768px) {
        .main-content {
            width: 100% !important;
            margin-left: 0 !important;
        }
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-plus-circle me-2"></i>Credit Wallet
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/wallets'); ?>" class="btn btn-sm btn-outline-secondary">
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

        <!-- User Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>User Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-2"><?php echo $user->first_name . ' ' . $user->last_name; ?></h5>
                        <p class="text-muted mb-1">
                            <i class="fas fa-envelope me-2"></i><?php echo $user->email; ?>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-phone me-2"></i><?php echo isset($user->phone) ? $user->phone : 'Not provided'; ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="current-balance">
                            <h4 class="text-success mb-1">Current Balance</h4>
                            <h2 class="text-success">$<?php echo number_format($wallet->balance, 2); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Credit Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2 text-success"></i>Credit Wallet
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('admin/credit_wallet/' . $user->id); ?>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount to Credit *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           id="amount" 
                                           name="amount" 
                                           step="0.01" 
                                           min="0.01" 
                                           required 
                                           placeholder="0.00">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason for Credit *</label>
                                <select class="form-select" id="reason" name="reason" required>
                                    <option value="">Select Reason</option>
                                    <option value="manual_credit">Manual Credit</option>
                                    <option value="refund">Refund</option>
                                    <option value="bonus">Bonus</option>
                                    <option value="compensation">Compensation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Enter any additional notes..."></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo base_url('admin/wallets'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i>Credit Wallet
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Recent Transactions
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_transactions)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recent_transactions as $transaction): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-bold"><?php echo $transaction->description; ?></div>
                                            <small class="text-muted"><?php echo date('M j, Y H:i', strtotime($transaction->created_at)); ?></small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-<?php echo $transaction->type === 'credit' ? 'success' : 'danger'; ?>">
                                                <?php echo $transaction->type === 'credit' ? '+' : '-'; ?>$<?php echo number_format($transaction->amount, 2); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-3">
                                <a href="<?php echo base_url('admin/wallet_transactions/' . $user->id); ?>" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-list me-1"></i>View All Transactions
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No transactions found.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('amount').addEventListener('input', function() {
    const amount = parseFloat(this.value) || 0;
    const currentBalance = <?php echo $wallet->balance; ?>;
    const newBalance = currentBalance + amount;
    
    // Update the preview if needed
    const balanceElement = document.querySelector('.current-balance h2');
    if (balanceElement) {
        balanceElement.textContent = '$' + newBalance.toFixed(2);
    }
});
</script>

<?php $this->load->view('admin/includes/footer'); ?> 