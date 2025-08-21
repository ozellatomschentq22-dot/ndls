<?php $this->load->view('admin/includes/header'); ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-building me-2"></i><?php echo isset($center) ? 'Edit Center' : 'Add New Center'; ?>
                </h1>
                <p class="text-muted"><?php echo isset($center) ? 'Update center information' : 'Create a new drop shipment center'; ?></p>
            </div>
            <div>
                <a href="<?php echo base_url('centers'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Centers
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?php echo isset($center) ? 'Edit Center: ' . htmlspecialchars($center->name) : 'Center Information'; ?>
                </h6>
            </div>
            <div class="card-body">
                <?php if (validation_errors()): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Please correct the following errors:
                        <ul class="mb-0 mt-2">
                            <?php echo validation_errors('<li>', '</li>'); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <?php echo form_open(current_url(), ['class' => 'needs-validation', 'novalidate' => '']); ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-building me-1"></i>Center Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo set_value('name', isset($center) ? $center->name : ''); ?>" 
                                   placeholder="Enter center name"
                                   required>
                            <div class="invalid-feedback">
                                Please provide a center name.
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Enter a unique name for this center (e.g., "Mumbai Warehouse", "Delhi Distribution Center")
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="location" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>Location
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="location" 
                                   name="location" 
                                   value="<?php echo set_value('location', isset($center) ? $center->location : ''); ?>" 
                                   placeholder="Enter center location">
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Optional: Specify the physical location or address of the center
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($center)): ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on me-1"></i>Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select <?php echo form_error('status') ? 'is-invalid' : ''; ?>" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="">Select Status</option>
                                <option value="active" <?php echo set_select('status', 'active', isset($center) && $center->status === 'active'); ?>>
                                    <i class="fas fa-check-circle"></i> Active
                                </option>
                                <option value="inactive" <?php echo set_select('status', 'inactive', isset($center) && $center->status === 'inactive'); ?>>
                                    <i class="fas fa-pause-circle"></i> Inactive
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a status.
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Active centers can be selected for new orders. Inactive centers are hidden from order creation.
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-calendar me-1"></i>Created Date
                            </label>
                            <div class="form-control-plaintext">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <?php echo date('F j, Y \a\t g:i A', strtotime($center->created_at)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <a href="<?php echo base_url('centers'); ?>" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-<?php echo isset($center) ? 'save' : 'plus'; ?> me-1"></i>
                        <?php echo isset($center) ? 'Update Center' : 'Create Center'; ?>
                    </button>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>

        <?php if (isset($center)): ?>
        <!-- Center Statistics -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar me-1"></i>Center Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-primary mb-1">0</div>
                            <div class="text-muted">Total Orders</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-warning mb-1">0</div>
                            <div class="text-muted">Pending Orders</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-success mb-1">0</div>
                            <div class="text-muted">Processed Orders</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="h4 text-info mb-1">₹0.00</div>
                            <div class="text-muted">Total Amount</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Auto-resize textarea
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(function(textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
});
</script>

<?php $this->load->view('admin/includes/footer'); ?> 