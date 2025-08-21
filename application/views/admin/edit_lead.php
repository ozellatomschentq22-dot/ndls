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
    
    .form-section {
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-section h6 {
        color: #495057;
        margin-bottom: 1rem;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 0.5rem;
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-edit me-2"></i>Edit Lead
                </h1>
                <p class="text-muted mb-0">Update lead information and status</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('admin/leads'); ?>" class="btn btn-outline-secondary btn-sm">
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

        <!-- Edit Lead Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>Edit Lead: <?php echo htmlspecialchars($lead->first_name . ' ' . $lead->last_name); ?>
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open('admin/edit_lead/' . $lead->id); ?>
                
                <!-- Contact Information -->
                <div class="form-section">
                    <h6><i class="fas fa-user me-2"></i>Contact Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                   value="<?php echo set_value('first_name', $lead->first_name); ?>" required>
                            <?php echo form_error('first_name', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                   value="<?php echo set_value('last_name', $lead->last_name); ?>" required>
                            <?php echo form_error('last_name', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo set_value('email', $lead->email); ?>" required>
                            <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo set_value('phone', $lead->phone); ?>" required>
                            <?php echo form_error('phone', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="form-section">
                    <h6><i class="fas fa-map-marker-alt me-2"></i>Address Information</h6>
                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address Line 1 *</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" 
                               value="<?php echo set_value('address_line1', $lead->address_line1); ?>" required>
                        <?php echo form_error('address_line1', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2" 
                               value="<?php echo set_value('address_line2', $lead->address_line2); ?>">
                        <?php echo form_error('address_line2', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="city" name="city" 
                                   value="<?php echo set_value('city', $lead->city); ?>" required>
                            <?php echo form_error('city', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State *</label>
                            <input type="text" class="form-control" id="state" name="state" 
                                   value="<?php echo set_value('state', $lead->state); ?>" required>
                            <?php echo form_error('state', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="postal_code" class="form-label">Postal Code *</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                   value="<?php echo set_value('postal_code', $lead->postal_code); ?>" required>
                            <?php echo form_error('postal_code', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" 
                               value="<?php echo set_value('country', $lead->country ?: 'USA'); ?>">
                        <?php echo form_error('country', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <!-- Product Interest -->
                <div class="form-section">
                    <h6><i class="fas fa-pills me-2"></i>Product Interest</h6>
                    <div class="mb-3">
                        <label for="product_interest" class="form-label">Product Interest</label>
                        <textarea class="form-control" id="product_interest" name="product_interest" rows="3" 
                                  placeholder="What products is this lead interested in?"><?php echo set_value('product_interest', $lead->product_interest); ?></textarea>
                        <?php echo form_error('product_interest', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="form-section">
                    <h6><i class="fas fa-credit-card me-2"></i>Payment Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method">
                                <option value="">Select Payment Method</option>
                                <option value="Credit Card" <?php echo set_select('payment_method', 'Credit Card', $lead->payment_method == 'Credit Card'); ?>>Credit Card</option>
                                <option value="Debit Card" <?php echo set_select('payment_method', 'Debit Card', $lead->payment_method == 'Debit Card'); ?>>Debit Card</option>
                                <option value="PayPal" <?php echo set_select('payment_method', 'PayPal', $lead->payment_method == 'PayPal'); ?>>PayPal</option>
                                <option value="Cash On Delivery" <?php echo set_select('payment_method', 'Cash On Delivery', $lead->payment_method == 'Cash On Delivery'); ?>>Cash On Delivery</option>
                                <option value="Bank Transfer" <?php echo set_select('payment_method', 'Bank Transfer', $lead->payment_method == 'Bank Transfer'); ?>>Bank Transfer</option>
                                <option value="Zelle" <?php echo set_select('payment_method', 'Zelle', $lead->payment_method == 'Zelle'); ?>>Zelle</option>
                                <option value="Venmo" <?php echo set_select('payment_method', 'Venmo', $lead->payment_method == 'Venmo'); ?>>Venmo</option>
                                <option value="Cash App" <?php echo set_select('payment_method', 'Cash App', $lead->payment_method == 'Cash App'); ?>>Cash App</option>
                            </select>
                            <?php echo form_error('payment_method', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payment_details" class="form-label">Payment Details</label>
                            <textarea class="form-control" id="payment_details" name="payment_details" rows="3" 
                                      placeholder="Payment details, card info, etc."><?php echo set_value('payment_details', $lead->payment_details); ?></textarea>
                            <?php echo form_error('payment_details', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                </div>

                <!-- Lead Status and Source -->
                <div class="form-section">
                    <h6><i class="fas fa-chart-line me-2"></i>Lead Management</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Lead Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="new" <?php echo set_select('status', 'new', $lead->status == 'new'); ?>>New</option>
                                <option value="contacted" <?php echo set_select('status', 'contacted', $lead->status == 'contacted'); ?>>Contacted</option>
                                <option value="qualified" <?php echo set_select('status', 'qualified', $lead->status == 'qualified'); ?>>Qualified</option>
                                <option value="converted" <?php echo set_select('status', 'converted', $lead->status == 'converted'); ?>>Converted</option>
                                <option value="lost" <?php echo set_select('status', 'lost', $lead->status == 'lost'); ?>>Lost</option>
                            </select>
                            <small class="form-text text-muted">
                                <strong>New:</strong> Just added, not contacted yet<br>
                                <strong>Contacted:</strong> Initial contact made (call, email, etc.)<br>
                                <strong>Qualified:</strong> Lead shows interest and is ready to buy<br>
                                <strong>Converted:</strong> Lead became a customer<br>
                                <strong>Lost:</strong> Lead is no longer interested
                            </small>
                            <?php echo form_error('status', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="source" class="form-label">Lead Source</label>
                            <select class="form-select" id="source" name="source">
                                <option value="manual" <?php echo set_select('source', 'manual', $lead->source == 'manual'); ?>>Manual Entry</option>
                                <option value="website" <?php echo set_select('source', 'website', $lead->source == 'website'); ?>>Website</option>
                                <option value="referral" <?php echo set_select('source', 'referral', $lead->source == 'referral'); ?>>Referral</option>
                                <option value="social_media" <?php echo set_select('source', 'social_media', $lead->source == 'social_media'); ?>>Social Media</option>
                                <option value="email_campaign" <?php echo set_select('source', 'email_campaign', $lead->source == 'email_campaign'); ?>>Email Campaign</option>
                                <option value="phone_call" <?php echo set_select('source', 'phone_call', $lead->source == 'phone_call'); ?>>Phone Call</option>
                                <option value="walk_in" <?php echo set_select('source', 'walk_in', $lead->source == 'walk_in'); ?>>Walk In</option>
                                <option value="other" <?php echo set_select('source', 'other', $lead->source == 'other'); ?>>Other</option>
                            </select>
                            <?php echo form_error('source', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="form-section">
                    <h6><i class="fas fa-sticky-note me-2"></i>Notes</h6>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="4" 
                                  placeholder="Any additional notes about this lead..."><?php echo set_value('notes', $lead->notes); ?></textarea>
                        <?php echo form_error('notes', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between">
                    <a href="<?php echo base_url('admin/leads'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Cancel
                    </a>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Lead
                        </button>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 6) {
        value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    } else if (value.length >= 3) {
        value = value.replace(/(\d{3})(\d{0,3})/, '$1-$2');
    }
    e.target.value = value;
});

// Postal code formatting
document.getElementById('postal_code').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 5) {
        value = value.replace(/(\d{5})(\d{0,4})/, '$1-$2');
    }
    e.target.value = value;
});
</script>

<?php $this->load->view('admin/includes/footer'); ?> 