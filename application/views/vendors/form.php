<div class="d-flex">
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-<?php echo isset($vendor) ? 'edit' : 'plus'; ?> me-2"></i>
                <?php echo isset($vendor) ? 'Edit' : 'Add'; ?> Vendor
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('vendors'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to List
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

        <!-- Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-handshake me-2"></i>
                    Vendor Information
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open(isset($vendor) ? 'vendors/edit/' . $vendor->id : 'vendors/add', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo form_error('name') ? 'is-invalid' : ''; ?>" 
                                   id="name" name="name" 
                                   value="<?php echo set_value('name', isset($vendor) ? $vendor->name : ''); ?>" 
                                   placeholder="Enter vendor name" required>
                            <?php if (form_error('name')): ?>
                                <div class="invalid-feedback"><?php echo form_error('name'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?php echo form_error('status') ? 'is-invalid' : ''; ?>" 
                                    id="status" name="status" required>
                                <option value="">Select Status</option>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?php echo $status; ?>" 
                                            <?php echo set_select('status', $status, isset($vendor) && $vendor->status === $status); ?>>
                                        <?php echo ucfirst($status); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (form_error('status')): ?>
                                <div class="invalid-feedback"><?php echo form_error('status'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="payout_type" class="form-label">Payout Type <span class="text-danger">*</span></label>
                            <select class="form-select <?php echo form_error('payout_type') ? 'is-invalid' : ''; ?>" 
                                    id="payout_type" name="payout_type" required>
                                <option value="">Select Payout Type</option>
                                <?php foreach ($payout_types as $type): ?>
                                    <option value="<?php echo $type; ?>" 
                                            <?php echo set_select('payout_type', $type, isset($vendor) && $vendor->payout_type === $type); ?>>
                                        <?php echo ucfirst($type); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (form_error('payout_type')): ?>
                                <div class="invalid-feedback"><?php echo form_error('payout_type'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Flat Rate Fields -->
                <div id="flat_rate_fields" class="row" style="display: none;">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="flat_rate_inr" class="form-label">Flat Rate (INR) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" class="form-control <?php echo form_error('flat_rate_inr') ? 'is-invalid' : ''; ?>" 
                                       id="flat_rate_inr" name="flat_rate_inr" 
                                       value="<?php echo set_value('flat_rate_inr', isset($vendor) ? $vendor->flat_rate_inr : ''); ?>" 
                                       placeholder="71.00">
                                <?php if (form_error('flat_rate_inr')): ?>
                                    <div class="invalid-feedback"><?php echo form_error('flat_rate_inr'); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Amount in INR for $1 USD (e.g., 71.00)</div>
                        </div>
                    </div>
                </div>

                <!-- Percentage Fields -->
                <div id="percentage_fields" class="row" style="display: none;">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="percentage_rate" class="form-label">Percentage Rate <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control <?php echo form_error('percentage_rate') ? 'is-invalid' : ''; ?>" 
                                       id="percentage_rate" name="percentage_rate" 
                                       value="<?php echo set_value('percentage_rate', isset($vendor) ? $vendor->percentage_rate : ''); ?>" 
                                       placeholder="85.00">
                                <span class="input-group-text">%</span>
                                <?php if (form_error('percentage_rate')): ?>
                                    <div class="invalid-feedback"><?php echo form_error('percentage_rate'); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Percentage of amount to pay (e.g., 85.00)</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="percentage_inr_rate" class="form-label">INR Rate <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" step="0.01" class="form-control <?php echo form_error('percentage_inr_rate') ? 'is-invalid' : ''; ?>" 
                                       id="percentage_inr_rate" name="percentage_inr_rate" 
                                       value="<?php echo set_value('percentage_inr_rate', isset($vendor) ? $vendor->percentage_inr_rate : ''); ?>" 
                                       placeholder="80.00">
                                <?php if (form_error('percentage_inr_rate')): ?>
                                    <div class="invalid-feedback"><?php echo form_error('percentage_inr_rate'); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">INR rate for percentage calculation (e.g., 80.00)</div>
                        </div>
                    </div>
                </div>

                <!-- Payout Preview -->
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-calculator me-2"></i>Payout Preview</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="preview_amount" class="form-label">Test Amount (USD)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control" id="preview_amount" value="100.00">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Vendor Payout</label>
                                        <div class="form-control-plaintext" id="vendor_payout">-</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">System Profit</label>
                                        <div class="form-control-plaintext" id="system_profit">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a href="<?php echo base_url('vendors'); ?>" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-<?php echo isset($vendor) ? 'save' : 'plus'; ?> me-1"></i>
                                <?php echo isset($vendor) ? 'Update' : 'Add'; ?> Vendor
                            </button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
// Show/hide fields based on payout type
document.getElementById('payout_type').addEventListener('change', function() {
    const payoutType = this.value;
    const flatFields = document.getElementById('flat_rate_fields');
    const percentageFields = document.getElementById('percentage_fields');
    
    if (payoutType === 'flat') {
        flatFields.style.display = 'block';
        percentageFields.style.display = 'none';
    } else if (payoutType === 'percentage') {
        flatFields.style.display = 'none';
        percentageFields.style.display = 'block';
    } else {
        flatFields.style.display = 'none';
        percentageFields.style.display = 'none';
    }
    
    // Trigger payout preview update
    updatePayoutPreview();
});

// Update payout preview
function updatePayoutPreview() {
    const payoutType = document.getElementById('payout_type').value;
    const amount = parseFloat(document.getElementById('preview_amount').value) || 0;
    
    if (payoutType === 'flat') {
        const flatRate = parseFloat(document.getElementById('flat_rate_inr').value) || 0;
        const vendorPayout = amount * flatRate;
        document.getElementById('vendor_payout').textContent = `₹${vendorPayout.toFixed(2)}`;
    } else if (payoutType === 'percentage') {
        const percentageRate = parseFloat(document.getElementById('percentage_rate').value) || 0;
        const inrRate = parseFloat(document.getElementById('percentage_inr_rate').value) || 0;
        const vendorPayout = (amount * (percentageRate / 100)) * inrRate;
        document.getElementById('vendor_payout').textContent = `₹${vendorPayout.toFixed(2)}`;
    } else {
        document.getElementById('vendor_payout').textContent = '-';
    }
    
    // System profit calculation (88% at ₹82)
    const systemProfit = (amount * 0.88) * 82;
    document.getElementById('system_profit').textContent = `₹${systemProfit.toFixed(2)}`;
}

// Add event listeners for real-time preview
document.getElementById('preview_amount').addEventListener('input', updatePayoutPreview);
document.getElementById('flat_rate_inr').addEventListener('input', updatePayoutPreview);
document.getElementById('percentage_rate').addEventListener('input', updatePayoutPreview);
document.getElementById('percentage_inr_rate').addEventListener('input', updatePayoutPreview);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Show appropriate fields based on current payout type
    const payoutType = document.getElementById('payout_type').value;
    if (payoutType) {
        document.getElementById('payout_type').dispatchEvent(new Event('change'));
    }
    
    // Initial preview update
    updatePayoutPreview();
});

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
</script> 