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
                <i class="fas fa-edit me-2"></i>Edit Payment Method
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
                        <?php echo form_open('admin/edit_payment_method/' . $method->id); ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="display_name" class="form-label">Display Name *</label>
                                    <input type="text" class="form-control" id="display_name" name="display_name" 
                                           value="<?php echo set_value('display_name', $method->display_name); ?>" required>
                                    <?php echo form_error('display_name', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="icon" class="form-label">Icon Class *</label>
                                    <input type="text" class="form-control" id="icon" name="icon" 
                                           value="<?php echo set_value('icon', $method->icon); ?>" required>
                                    <?php echo form_error('icon', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                           value="<?php echo set_value('sort_order', $method->sort_order); ?>" min="0">
                                    <?php echo form_error('sort_order', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?php echo set_value('title', $method->title); ?>" required>
                                    <?php echo form_error('title', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="instructions" class="form-label">Instructions</label>
                                <textarea class="form-control" id="instructions" name="instructions" rows="3" 
                                          placeholder="Enter instructions for users on how to use this payment method..."><?php echo set_value('instructions', $method->instructions); ?></textarea>
                                <?php echo form_error('instructions', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label for="additional_info" class="form-label">Additional Info</label>
                                <textarea class="form-control" id="additional_info" name="additional_info" rows="3" 
                                          placeholder="Any additional information or tips for users..."><?php echo set_value('additional_info', $method->additional_info); ?></textarea>
                                <?php echo form_error('additional_info', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           <?php echo $method->is_active ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active">
                                        Active Payment Method
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo base_url('admin/payment_methods'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Payment Method
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
                            <h6 class="text-primary">Display Name</h6>
                            <small class="text-muted">This is the name that will be shown to users when selecting payment methods.</small>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-info">Icon Classes</h6>
                            <small class="text-muted">Use Font Awesome icon classes (e.g., fas fa-credit-card, fab fa-paypal, etc.)</small>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-warning">Sort Order</h6>
                            <small class="text-muted">Lower numbers appear first. Use 0 for default ordering.</small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Changes will be reflected immediately for users.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 