<?php
$this->load->view('customer/common/header');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-credit-card me-2"></i>Request Wallet Recharge
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

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Recharge Request Form
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open('customer/recharge'); ?>
                    
                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Recharge Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="1" required placeholder="Enter amount">
                        </div>
                        <div class="form-text">Minimum amount: $1.00</div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label for="payment_mode" class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_mode" id="payment_mode" class="form-select" required>
                            <option value="">Select payment method...</option>
                            <?php foreach ($payment_instructions as $method): ?>
                                <option value="<?php echo $method->id; ?>"><?php echo htmlspecialchars($method->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Payment Instructions -->
                    <div id="payment-instructions" class="mb-3" style="display: none;">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Payment Instructions</h6>
                                <div id="instructions-content"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction ID -->
                    <div class="mb-3">
                        <label for="transaction_id" class="form-label">Transaction ID <span class="text-danger">*</span></label>
                        <input type="text" name="transaction_id" id="transaction_id" class="form-control" required placeholder="Enter transaction ID or reference number">
                        <div class="form-text">Provide the transaction ID from your payment method</div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes (Optional)</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any additional information about your payment..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit Recharge Request
                        </button>
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Current Balance -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-wallet me-2"></i>Current Balance
                </h5>
            </div>
            <div class="card-body text-center">
                <h3 class="mb-2 <?php echo ($wallet && $wallet->balance < 0) ? 'text-danger' : 'text-success'; ?>">
                    $<?php echo number_format($wallet ? $wallet->balance : 0, 2); ?>
                </h3>
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

        <!-- Payment Methods Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Payment Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>How it works:</h6>
                    <ol class="small">
                        <li>Select your payment method</li>
                        <li>Follow the payment instructions</li>
                        <li>Enter the transaction ID</li>
                        <li>Submit your request</li>
                        <li>Admin will review and approve</li>
                    </ol>
                </div>
                
                <div class="mb-3">
                    <h6>Processing Time:</h6>
                    <ul class="small text-muted">
                        <li>Requests reviewed within 24 hours</li>
                        <li>Funds added immediately after approval</li>
                        <li>You'll receive email confirmation</li>
                    </ul>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?php echo base_url('customer/pending_recharges'); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-clock me-2"></i>View Pending Requests
                    </a>
                    <a href="<?php echo base_url('customer/wallet'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-history me-2"></i>Transaction History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Show payment instructions when method is selected
    document.getElementById('payment_mode').addEventListener('change', function() {
        const instructionsDiv = document.getElementById('payment-instructions');
        const instructionsContent = document.getElementById('instructions-content');
        
        if (this.value) {
            // Find the selected payment method
            const selectedMethod = <?php echo json_encode($payment_instructions); ?>.find(method => method.id == this.value);
            
            if (selectedMethod && selectedMethod.instructions) {
                instructionsContent.innerHTML = selectedMethod.instructions;
                instructionsDiv.style.display = 'block';
            } else {
                instructionsDiv.style.display = 'none';
            }
        } else {
            instructionsDiv.style.display = 'none';
        }
    });
</script>

<?php $this->load->view('customer/common/footer'); ?> 