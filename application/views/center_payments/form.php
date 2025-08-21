<?php $this->load->view('admin/includes/header'); ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-money-bill-wave me-2"></i><?php echo isset($payment) ? 'Edit' : 'Add'; ?> Center Payment
                </h1>
                <p class="text-muted">Record payment received from drop shipment center</p>
            </div>
            <div>
                <a href="<?php echo base_url('center_payments'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Payments
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                    </div>
                    <div class="card-body">
                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Please correct the following errors:
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <?php echo form_open(current_url()); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="center_name">Center Name <span class="text-danger">*</span></label>
                                        <select name="center_name" id="center_name" class="form-control" required>
                                            <option value="">Select Center</option>
                                            <?php foreach ($centers as $center): ?>
                                                <option value="<?php echo $center->name; ?>" 
                                                        <?php echo set_select('center_name', $center->name, (isset($payment) && $payment->center_name === $center->name)); ?>>
                                                    <?php echo htmlspecialchars($center->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amount_paid">Amount Paid <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" name="amount_paid" id="amount_paid" class="form-control" 
                                                   value="<?php echo set_value('amount_paid', isset($payment) ? $payment->amount_paid : ''); ?>" 
                                                   step="0.01" min="0.01" placeholder="Enter amount" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                        <input type="date" name="payment_date" id="payment_date" class="form-control" 
                                               value="<?php echo set_value('payment_date', isset($payment) ? $payment->payment_date : date('Y-m-d')); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                            <option value="">Select Method</option>
                                            <?php foreach ($payment_methods as $key => $value): ?>
                                                <option value="<?php echo $key; ?>" 
                                                        <?php echo set_select('payment_method', $key, (isset($payment) && $payment->payment_method === $key)); ?>>
                                                    <?php echo $value; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control" required>
                                            <?php foreach ($payment_statuses as $key => $value): ?>
                                                <option value="<?php echo $key; ?>" 
                                                        <?php echo set_select('status', $key, (isset($payment) && $payment->status === $key) ? true : ($key === 'Completed' && !isset($payment))); ?>>
                                                    <?php echo $value; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reference_number">Reference Number</label>
                                        <input type="text" name="reference_number" id="reference_number" class="form-control" 
                                               value="<?php echo set_value('reference_number', isset($payment) ? $payment->reference_number : ''); ?>" 
                                               placeholder="Transaction ID, Check #, etc.">
                                        <small class="form-text text-muted">Optional reference number for tracking</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" 
                                          placeholder="Additional notes about this payment"><?php echo set_value('notes', isset($payment) ? $payment->notes : ''); ?></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i><?php echo isset($payment) ? 'Update' : 'Record'; ?> Payment
                                </button>
                                <a href="<?php echo base_url('center_payments'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Center Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Center Information</h6>
                    </div>
                    <div class="card-body">
                        <div id="center-info" class="text-center py-4">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Select Center to view details</h6>
                            <p class="text-muted small">Center information will appear here when you select a center</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Guidelines -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment Guidelines</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-1"></i>Important Notes</h6>
                            <ul class="mb-0">
                                <li>Centers pay in bulk amounts, not per order</li>
                                <li>Payment date should be when the center actually paid</li>
                                <li>Reference number helps track the transaction</li>
                                <li>Outstanding amounts are calculated automatically</li>
                            </ul>
                        </div>

                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-1"></i>Payment Status</h6>
                            <ul class="mb-0">
                                <li><strong>Pending:</strong> Payment expected but not received</li>
                                <li><strong>Completed:</strong> Payment received successfully</li>
                                <li><strong>Failed:</strong> Payment attempt failed</li>
                                <li><strong>Refunded:</strong> Payment was refunded</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('center_name').addEventListener('change', function() {
    const centerName = this.value;
    const centerInfo = document.getElementById('center-info');
    
    if (!centerName) {
        centerInfo.innerHTML = `
            <i class="fas fa-building fa-3x text-muted mb-3"></i>
            <h6 class="text-muted">Select Center to view details</h6>
            <p class="text-muted small">Center information will appear here when you select a center</p>
        `;
        return;
    }
    
    // Show loading
    centerInfo.innerHTML = `
        <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
        <h6 class="text-primary">Loading center details...</h6>
    `;
    
    // Fetch center details
    fetch('<?php echo base_url('center_payments/get_center_details'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'center_name=' + encodeURIComponent(centerName)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const center = data.center;
            centerInfo.innerHTML = `
                <div class="text-left">
                    <h6 class="font-weight-bold text-primary">${center.center_name}</h6>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Total Orders:</strong></td>
                            <td>${center.total_orders}</td>
                        </tr>
                        <tr>
                            <td><strong>Order Value:</strong></td>
                            <td class="text-primary">₹${parseFloat(center.total_value).toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Paid:</strong></td>
                            <td class="text-success">₹${parseFloat(center.total_paid).toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td><strong>Outstanding:</strong></td>
                            <td class="text-${center.outstanding > 0 ? 'danger' : center.outstanding < 0 ? 'warning' : 'success'}">
                                ₹${parseFloat(Math.abs(center.outstanding)).toFixed(2)}
                                ${center.outstanding < 0 ? ' (Overpaid)' : center.outstanding > 0 ? ' (Due)' : ' (Paid)'}
                            </td>
                        </tr>
                    </table>
                </div>
            `;
        } else {
            centerInfo.innerHTML = `
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <h6 class="text-danger">Center not found</h6>
                <p class="text-muted small">Please check the center name and try again</p>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        centerInfo.innerHTML = `
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
            <h6 class="text-danger">Error loading center</h6>
            <p class="text-muted small">Please try again</p>
        `;
    });
});
</script>

<?php $this->load->view('admin/includes/footer'); ?> 