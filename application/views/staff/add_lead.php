<?php $this->load->view('staff/includes/header'); ?>

<style>
    .main-content {
        margin-left: 280px;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }
    
    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-section h5 {
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
    }
</style>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-user-plus me-2"></i>Add New Lead
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('staff/leads'); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Leads
                </a>
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

        <!-- Add Lead Form -->
        <div class="card">
            <div class="card-body">
                <?php echo form_open('staff/add_lead'); ?>
                    
                    <!-- Contact Information -->
                    <div class="form-section">
                        <h5><i class="fas fa-user me-2"></i>Contact Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo set_value('first_name'); ?>" required>
                                    <?php echo form_error('first_name', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo set_value('last_name'); ?>" required>
                                    <?php echo form_error('last_name', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo set_value('email'); ?>" required>
                                    <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo set_value('phone'); ?>" required>
                                    <?php echo form_error('phone', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="form-section">
                        <h5><i class="fas fa-map-marker-alt me-2"></i>Address Information</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address_line1" class="form-label">Address Line 1 *</label>
                                    <input type="text" class="form-control" id="address_line1" name="address_line1" 
                                           value="<?php echo set_value('address_line1'); ?>" required>
                                    <?php echo form_error('address_line1', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address_line2" class="form-label">Address Line 2</label>
                                    <input type="text" class="form-control" id="address_line2" name="address_line2" 
                                           value="<?php echo set_value('address_line2'); ?>">
                                    <?php echo form_error('address_line2', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control" id="city" name="city" 
                                           value="<?php echo set_value('city'); ?>" required>
                                    <?php echo form_error('city', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state" class="form-label">State/Province *</label>
                                    <input type="text" class="form-control" id="state" name="state" 
                                           value="<?php echo set_value('state'); ?>" required>
                                    <?php echo form_error('state', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Postal Code *</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                           value="<?php echo set_value('postal_code'); ?>" required>
                                    <?php echo form_error('postal_code', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <select class="form-select" id="country" name="country">
                                        <option value="USA" <?php echo set_select('country', 'USA', TRUE); ?>>United States</option>
                                        <option value="Canada" <?php echo set_select('country', 'Canada'); ?>>Canada</option>
                                        <option value="UK" <?php echo set_select('country', 'UK'); ?>>United Kingdom</option>
                                        <option value="Australia" <?php echo set_select('country', 'Australia'); ?>>Australia</option>
                                        <option value="Other" <?php echo set_select('country', 'Other'); ?>>Other</option>
                                    </select>
                                    <?php echo form_error('country', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Interest -->
                    <div class="form-section">
                        <h5><i class="fas fa-shopping-cart me-2"></i>Product Interest</h5>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="product_interest" class="form-label">Product Interest</label>
                                    <textarea class="form-control" id="product_interest" name="product_interest" 
                                              rows="3" placeholder="e.g., Soma 350 mg 180 pills $299"><?php echo set_value('product_interest'); ?></textarea>
                                    <?php echo form_error('product_interest', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="form-section">
                        <h5><i class="fas fa-credit-card me-2"></i>Payment Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-select" id="payment_method" name="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="Credit Card" <?php echo set_select('payment_method', 'Credit Card'); ?>>Credit Card</option>
                                        <option value="Debit Card" <?php echo set_select('payment_method', 'Debit Card'); ?>>Debit Card</option>
                                        <option value="PayPal" <?php echo set_select('payment_method', 'PayPal'); ?>>PayPal</option>
                                        <option value="Bank Transfer" <?php echo set_select('payment_method', 'Bank Transfer'); ?>>Bank Transfer</option>
                                        <option value="Cash" <?php echo set_select('payment_method', 'Cash'); ?>>Cash</option>
                                        <option value="Other" <?php echo set_select('payment_method', 'Other'); ?>>Other</option>
                                    </select>
                                    <?php echo form_error('payment_method', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="source" class="form-label">Lead Source</label>
                                    <select class="form-select" id="source" name="source">
                                        <option value="manual" <?php echo set_select('source', 'manual', TRUE); ?>>Manual Entry</option>
                                        <option value="website" <?php echo set_select('source', 'website'); ?>>Website</option>
                                        <option value="phone" <?php echo set_select('source', 'phone'); ?>>Phone Call</option>
                                        <option value="email" <?php echo set_select('source', 'email'); ?>>Email</option>
                                        <option value="referral" <?php echo set_select('source', 'referral'); ?>>Referral</option>
                                        <option value="social_media" <?php echo set_select('source', 'social_media'); ?>>Social Media</option>
                                        <option value="advertising" <?php echo set_select('source', 'advertising'); ?>>Advertising</option>
                                        <option value="other" <?php echo set_select('source', 'other'); ?>>Other</option>
                                    </select>
                                    <?php echo form_error('source', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="payment_details" class="form-label">Payment Details</label>
                                    <textarea class="form-control" id="payment_details" name="payment_details" 
                                              rows="3" placeholder="e.g., CC Number: 4342562387827361; Name on Card: John Doe; CCV: 123; Expiry: 12/25"><?php echo set_value('payment_details'); ?></textarea>
                                    <?php echo form_error('payment_details', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="form-section">
                        <h5><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Lead Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="new" <?php echo set_select('status', 'new', TRUE); ?>>New</option>
                                        <option value="contacted" <?php echo set_select('status', 'contacted'); ?>>Contacted</option>
                                        <option value="qualified" <?php echo set_select('status', 'qualified'); ?>>Qualified</option>
                                        <option value="converted" <?php echo set_select('status', 'converted'); ?>>Converted</option>
                                        <option value="lost" <?php echo set_select('status', 'lost'); ?>>Lost</option>
                                    </select>
                                    <?php echo form_error('status', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" name="notes" 
                                              rows="4" placeholder="Additional notes about this lead..."><?php echo set_value('notes'); ?></textarea>
                                    <?php echo form_error('notes', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo base_url('staff/leads'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Add Lead
                        </button>
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-format phone number
document.getElementById('phone').addEventListener('blur', function() {
    let phone = this.value.replace(/\D/g, '');
    if (phone.length === 10) {
        phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    } else if (phone.length === 11 && phone.startsWith('1')) {
        phone = phone.substring(1).replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    }
    this.value = phone;
});

// Auto-format postal code for USA
document.getElementById('postal_code').addEventListener('blur', function() {
    let zipCode = this.value.replace(/\D/g, '');
    if (zipCode.length === 9) {
        zipCode = zipCode.substring(0, 5) + '-' + zipCode.substring(5);
    }
    this.value = zipCode;
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 