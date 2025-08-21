<?php
$this->load->view('customer/common/header');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-wallet me-2"></i>My Wallet
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

<!-- Wallet Balance Card -->
<div class="row mb-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-wallet me-2"></i>Current Balance
                </h5>
            </div>
            <div class="card-body text-center">
                <h2 class="mb-2 <?php echo ($wallet && $wallet->balance < 0) ? 'text-danger' : 'text-success'; ?>">
                    $<?php echo number_format($wallet ? $wallet->balance : 0, 2); ?>
                </h2>
                <?php if ($wallet && $wallet->balance < 0): ?>
                    <p class="text-danger mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>PAYMENT REQUIRED!</strong>
                    </p>
                <?php else: ?>
                    <p class="text-muted mb-0">Available for purchases</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo base_url('customer/recharge'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Request Recharge
                    </a>
                    <a href="<?php echo base_url('customer/pending_recharges'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-clock me-2"></i>View Pending Requests
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction History -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-history me-2"></i>Transaction History
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($transactions)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td>
                                <small class="text-muted">
                                    <?php echo date('M j, Y H:i', strtotime($transaction->created_at)); ?>
                                </small>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($transaction->description); ?></div>
                                <?php if ($transaction->reference_id): ?>
                                    <small class="text-muted">Ref: <?php echo $transaction->reference_id; ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $transaction->type === 'credit' ? 'success' : 'danger'; ?>">
                                    <?php echo ucfirst($transaction->type); ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold <?php echo $transaction->type === 'credit' ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $transaction->type === 'credit' ? '+' : '-'; ?>$<?php echo number_format($transaction->amount, 2); ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold <?php echo (isset($transaction->balance_after) && $transaction->balance_after < 0) ? 'text-danger' : 'text-success'; ?>">
                                    $<?php echo number_format(isset($transaction->balance_after) ? $transaction->balance_after : 0, 2); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-4">
                <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                <h4>No Transactions Yet</h4>
                <p class="text-muted">You haven't made any transactions yet.</p>
                <a href="<?php echo base_url('customer/recharge'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Make Your First Recharge
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->load->view('customer/common/footer'); ?> 