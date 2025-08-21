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
                <i class="fas fa-plus me-2"></i>Add Payment Method
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/payment_methods'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Payment Methods
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Method Information</h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('admin/add_payment_method', ['id' => 'addPaymentMethodForm']); ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="display_name" class="form-label">Display Name *</label>
                                    <input type="text" class="form-control" id="display_name" name="display_name" 
                                           value="<?php echo set_value('display_name'); ?>" required>
                                    <?php echo form_error('display_name', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Title/Description</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?php echo set_value('title'); ?>">
                                    <?php echo form_error('title', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                            <label for="method_key" class="form-label">Payment Mode *</label>
                                            <select class="form-select" id="method_key" name="method_key" required>
                                                <option value="">Select Payment Mode</option>
                                                <option value="credit_card" <?php echo set_select('method_key', 'credit_card'); ?>>Credit Card</option>
                                                <option value="debit_card" <?php echo set_select('method_key', 'debit_card'); ?>>Debit Card</option>
                                                <option value="bank_transfer" <?php echo set_select('method_key', 'bank_transfer'); ?>>Bank Transfer</option>
                                                <option value="paypal" <?php echo set_select('method_key', 'paypal'); ?>>PayPal</option>
                                                <option value="stripe" <?php echo set_select('method_key', 'stripe'); ?>>Stripe</option>
                                                <option value="cash_on_delivery" <?php echo set_select('method_key', 'cash_on_delivery'); ?>>Cash on Delivery</option>
                                                <option value="crypto" <?php echo set_select('method_key', 'crypto'); ?>>Cryptocurrency</option>
                                                <option value="other" <?php echo set_select('method_key', 'other'); ?>>Other</option>
                                            </select>
                                            <?php echo form_error('method_key', '<small class="text-danger">', '</small>'); ?>
                                        </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="icon" class="form-label">Icon Class *</label>
                                    <input type="text" class="form-control" id="icon" name="icon" 
                                           value="<?php echo set_value('icon'); ?>" placeholder="fas fa-credit-card" required>
                                    <?php echo form_error('icon', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                           value="<?php echo set_value('sort_order', '0'); ?>" min="0">
                                    <?php echo form_error('sort_order', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               <?php echo set_checkbox('is_active', '1', TRUE); ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active Payment Method
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" 
                                          placeholder="Enter detailed description of the payment method..."><?php echo set_value('description'); ?></textarea>
                                <?php echo form_error('description', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label for="instructions" class="form-label">Instructions</label>
                                <textarea class="form-control" id="instructions" name="instructions" rows="3" 
                                          placeholder="Enter instructions for users on how to use this payment method..."><?php echo set_value('instructions'); ?></textarea>
                                <?php echo form_error('instructions', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo base_url('admin/payment_methods'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Payment Method
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-primary">Payment Modes</h6>
                            <small class="text-muted">
                                Choose the appropriate payment mode that best describes this payment method.
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-info">Icon Classes</h6>
                            <small class="text-muted">
                                Use Font Awesome icon classes (e.g., fas fa-credit-card, fab fa-paypal, etc.)
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-warning">Sort Order</h6>
                            <small class="text-muted">
                                Lower numbers appear first. Use 0 for default ordering.
                            </small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Payment methods can be activated/deactivated after creation.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-fill icon based on payment mode selection
document.getElementById('method_key').addEventListener('change', function() {
    const iconMap = {
        'credit_card': 'fas fa-credit-card',
        'debit_card': 'fas fa-credit-card',
        'bank_transfer': 'fas fa-university',
        'paypal': 'fab fa-paypal',
        'stripe': 'fab fa-stripe-s',
        'cash_on_delivery': 'fas fa-money-bill-wave',
        'crypto': 'fab fa-bitcoin',
        'other': 'fas fa-credit-card'
    };
    
    const selectedMode = this.value;
    const iconField = document.getElementById('icon');
    
    if (iconMap[selectedMode]) {
        iconField.value = iconMap[selectedMode];
    }
});
</script>

<?php $this->load->view('admin/includes/footer'); ?> 