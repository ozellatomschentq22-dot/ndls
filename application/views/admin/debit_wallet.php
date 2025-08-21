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
                <i class="fas fa-minus-circle me-2"></i>Debit Wallet
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

        <div class="row">
            <div class="col-lg-8">
                <!-- Debit Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-minus-circle me-2 text-danger"></i>Debit Wallet
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('admin/debit_wallet/' . $user->id); ?>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount to Debit *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           id="amount" 
                                           name="amount" 
                                           step="0.01" 
                                           min="0.01" 
                                           required 
                                           placeholder="0.00"
                                           value="<?php echo set_value('amount'); ?>">
                                </div>
                                <small class="text-muted">Current balance: $<?php echo number_format($wallet->balance, 2); ?> (Debiting allowed even with zero balance)</small>
                                <?php echo form_error('amount', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="3" 
                                          required 
                                          placeholder="Enter reason for debit..."><?php echo set_value('description'); ?></textarea>
                                <?php echo form_error('description', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label for="reference_type" class="form-label">Reference Type</label>
                                <select class="form-select" id="reference_type" name="reference_type">
                                    <option value="manual" <?php echo set_select('reference_type', 'manual', TRUE); ?>>Manual Adjustment</option>
                                    <option value="refund" <?php echo set_select('reference_type', 'refund'); ?>>Refund</option>
                                    <option value="fee" <?php echo set_select('reference_type', 'fee'); ?>>Fee</option>
                                    <option value="other" <?php echo set_select('reference_type', 'other'); ?>>Other</option>
                                </select>
                                <?php echo form_error('reference_type', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label for="reference_id" class="form-label">Reference ID (Optional)</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="reference_id" 
                                       name="reference_id" 
                                       placeholder="e.g., Order #123, Ticket #456"
                                       value="<?php echo set_value('reference_id'); ?>">
                                <?php echo form_error('reference_id', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo base_url('admin/wallets'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-minus-circle me-1"></i>Debit Wallet
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer Name</label>
                            <p class="mb-0"><?php echo $user->first_name . ' ' . $user->last_name; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="mb-0"><?php echo $user->email; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="mb-0"><?php echo isset($user->phone) ? $user->phone : 'Not provided'; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Balance</label>
                            <p class="mb-0 text-danger fs-4">$<?php echo number_format($wallet->balance, 2); ?></p>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo base_url('admin/wallet_transactions/' . $user->id); ?>" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-history me-1"></i>View Transaction History
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-danger">Debit Amount</h6>
                            <small class="text-muted">Enter the amount you want to deduct from the customer's wallet.</small>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-warning">Description</h6>
                            <small class="text-muted">Provide a clear reason for the debit (e.g., "Refund for cancelled order", "Service fee").</small>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-info">Reference</h6>
                            <small class="text-muted">Link this debit to an order, ticket, or other transaction for tracking purposes.</small>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This action cannot be undone. Please verify the amount before proceeding.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Removed balance validation to allow debiting even with zero balance
    console.log('Debit wallet form loaded - balance validation disabled');
});
</script>

<?php $this->load->view('admin/includes/footer'); ?> 