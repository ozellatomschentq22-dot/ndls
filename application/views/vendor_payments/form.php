<div class="d-flex">
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-<?php echo isset($payment) ? 'edit' : 'plus'; ?> me-2"></i>
                <?php echo isset($payment) ? 'Edit' : 'Add'; ?> Vendor Payment
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('vendor_payments'); ?>" class="btn btn-sm btn-outline-secondary">
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
                    <i class="fas fa-money-bill-wave me-2"></i>
                    Payment Information
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open(isset($payment) ? 'vendor_payments/edit/' . $payment->id : 'vendor_payments/add', ['class' => 'needs-validation', 'novalidate' => '', 'enctype' => 'multipart/form-data']); ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control <?php echo form_error('date') ? 'is-invalid' : ''; ?>" 
                                   id="date" name="date" 
                                   value="<?php echo set_value('date', isset($payment) ? $payment->date : date('Y-m-d')); ?>" 
                                   required>
                            <?php if (form_error('date')): ?>
                                <div class="invalid-feedback"><?php echo form_error('date'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
                            <select class="form-select <?php echo form_error('vendor_id') ? 'is-invalid' : ''; ?>" 
                                    id="vendor_id" name="vendor_id" required>
                                <option value="">Select Vendor</option>
                                <?php foreach ($vendors as $vendor): ?>
                                    <option value="<?php echo $vendor->id; ?>" 
                                            <?php echo set_select('vendor_id', $vendor->id, isset($payment) && $payment->vendor_id == $vendor->id); ?>>
                                        <?php echo htmlspecialchars($vendor->name); ?> 
                                        (<?php echo ucfirst($vendor->payout_type); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (form_error('vendor_id')): ?>
                                <div class="invalid-feedback"><?php echo form_error('vendor_id'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sender" class="form-label">Sender <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo form_error('sender') ? 'is-invalid' : ''; ?>" 
                                   id="sender" name="sender" 
                                   value="<?php echo set_value('sender', isset($payment) ? $payment->sender : ''); ?>" 
                                   placeholder="Enter sender name" required>
                            <?php if (form_error('sender')): ?>
                                <div class="invalid-feedback"><?php echo form_error('sender'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="receiver" class="form-label">Receiver <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo form_error('receiver') ? 'is-invalid' : ''; ?>" 
                                   id="receiver" name="receiver" 
                                   value="<?php echo set_value('receiver', isset($payment) ? $payment->receiver : ''); ?>" 
                                   placeholder="Enter receiver name" required>
                            <?php if (form_error('receiver')): ?>
                                <div class="invalid-feedback"><?php echo form_error('receiver'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="mode" class="form-label">Payment Mode <span class="text-danger">*</span></label>
                            <select class="form-select <?php echo form_error('mode') ? 'is-invalid' : ''; ?>" 
                                    id="mode" name="mode" required>
                                <option value="">Select Payment Mode</option>
                                <?php foreach ($modes as $mode): ?>
                                    <option value="<?php echo $mode; ?>" 
                                            <?php echo set_select('mode', $mode, isset($payment) && $payment->mode === $mode); ?>>
                                        <?php echo $mode; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (form_error('mode')): ?>
                                <div class="invalid-feedback"><?php echo form_error('mode'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control <?php echo form_error('amount') ? 'is-invalid' : ''; ?>" 
                                       id="amount" name="amount" 
                                       value="<?php echo set_value('amount', isset($payment) ? number_format($payment->amount, 2) : ''); ?>" 
                                       placeholder="0.00" required>
                                <?php if (form_error('amount')): ?>
                                    <div class="invalid-feedback"><?php echo form_error('amount'); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">Enter amount (e.g., 1,250.50)</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?php echo form_error('status') ? 'is-invalid' : ''; ?>" 
                                    id="status" name="status" required>
                                <option value="">Select Status</option>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?php echo $status; ?>" 
                                            <?php echo set_select('status', $status, isset($payment) ? $payment->status === $status : $status === 'Pending'); ?>>
                                        <?php echo $status; ?>
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
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="screenshot" class="form-label">
                                <i class="fas fa-image me-1"></i>Screenshot/Proof of Payment
                            </label>
                            <input type="file" class="form-control <?php echo form_error('screenshot') ? 'is-invalid' : ''; ?>" 
                                   id="screenshot" name="screenshot" 
                                   accept="image/*,.pdf">
                            <div class="form-text">
                                Upload screenshot or proof of payment (JPG, PNG, GIF, PDF - Max 2MB)
                            </div>
                            <?php if (form_error('screenshot')): ?>
                                <div class="invalid-feedback"><?php echo form_error('screenshot'); ?></div>
                            <?php endif; ?>
                            
                            <?php if (isset($payment) && $payment->screenshot): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Current screenshot:</small>
                                    <div class="mt-1">
                                        <?php if (pathinfo($payment->screenshot, PATHINFO_EXTENSION) === 'pdf'): ?>
                                            <a href="<?php echo base_url($payment->screenshot); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-file-pdf me-1"></i>View PDF
                                            </a>
                                        <?php else: ?>
                                            <img src="<?php echo base_url($payment->screenshot); ?>" alt="Payment Screenshot" 
                                                 class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a href="<?php echo base_url('vendor_payments'); ?>" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-<?php echo isset($payment) ? 'save' : 'plus'; ?> me-1"></i>
                                <?php echo isset($payment) ? 'Update' : 'Add'; ?> Payment
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
// Format amount input with commas and decimal
document.getElementById('amount').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d.]/g, '');
    
    // Ensure only one decimal point
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }
    
    // Format with commas for thousands
    if (parts.length === 1) {
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    } else {
        const wholePart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        value = wholePart + '.' + parts[1];
    }
    
    e.target.value = value;
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