<div class="d-flex">
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-user me-2"></i>Vendor Profile: <?php echo htmlspecialchars($vendor->name); ?>
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('vendors'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Vendors
                    </a>
                    <a href="<?php echo base_url('vendors/edit/' . $vendor->id); ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Vendor
                    </a>
                    <a href="<?php echo base_url('vendor_payments/add'); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Payment
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

        <!-- Vendor Information -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Vendor Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold" style="width: 40%;">Name:</td>
                                        <td><?php echo htmlspecialchars($vendor->name); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status:</td>
                                        <td>
                                            <?php if ($vendor->status === 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Payout Type:</td>
                                        <td>
                                            <?php if ($vendor->payout_type === 'flat'): ?>
                                                <span class="badge bg-info">Flat Rate</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Percentage</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold" style="width: 40%;">Rate Details:</td>
                                        <td>
                                            <?php if ($vendor->payout_type === 'flat'): ?>
                                                <span class="text-success">$1 = ₹<?php echo number_format($vendor->flat_rate_inr, 2); ?></span>
                                            <?php else: ?>
                                                <span class="text-warning"><?php echo $vendor->percentage_rate; ?>% at ₹<?php echo number_format($vendor->percentage_inr_rate, 2); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Created By:</td>
                                        <td><?php echo htmlspecialchars($vendor->creator_first_name . ' ' . $vendor->creator_last_name); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Created:</td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($vendor->created_at)); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Payment Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="text-primary fw-bold h4"><?php echo $payment_summary->total_payments ?? 0; ?></div>
                                <div class="small text-muted">Total Payments</div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-success fw-bold h4">$<?php echo number_format($payment_summary->total_amount ?? 0, 2); ?></div>
                                <div class="small text-muted">Total Amount</div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="text-warning fw-bold h5"><?php echo $payment_summary->pending_count ?? 0; ?></div>
                                <div class="small text-muted">Pending</div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="text-success fw-bold h5"><?php echo $payment_summary->approved_count ?? 0; ?></div>
                                <div class="small text-muted">Approved</div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="text-danger fw-bold h5"><?php echo $payment_summary->declined_count ?? 0; ?></div>
                                <div class="small text-muted">Declined</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Payment Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo base_url('vendors/profile/' . $vendor->id); ?>" class="row g-3">
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?php echo $status; ?>" <?php echo ($filters['status'] == $status) ? 'selected' : ''; ?>>
                                    <?php echo $status; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="mode" class="form-label">Mode</label>
                        <select class="form-select" id="mode" name="mode">
                            <option value="">All Modes</option>
                            <?php foreach ($modes as $mode): ?>
                                <option value="<?php echo $mode; ?>" <?php echo ($filters['mode'] == $mode) ? 'selected' : ''; ?>>
                                    <?php echo $mode; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo $filters['date_from']; ?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo $filters['date_to']; ?>">
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="<?php echo base_url('vendors/profile/' . $vendor->id); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vendor Payments Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Payment Records for <?php echo htmlspecialchars($vendor->name); ?>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Mode</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($payments)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No payment records found for this vendor</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td><?php echo date('M j, Y', strtotime($payment->date)); ?></td>
                                        <td><?php echo htmlspecialchars($payment->sender); ?></td>
                                        <td><?php echo htmlspecialchars($payment->receiver); ?></td>
                                        <td>
                                            <span class="badge bg-info"><?php echo $payment->mode; ?></span>
                                        </td>
                                        <td class="fw-bold">$<?php echo number_format($payment->amount, 2); ?></td>
                                        <td>
                                            <?php if ($payment->status === 'Pending'): ?>
                                                <span class="badge bg-warning"><?php echo $payment->status; ?></span>
                                            <?php elseif ($payment->status === 'Approved'): ?>
                                                <span class="badge bg-success"><?php echo $payment->status; ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><?php echo $payment->status; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($payment->creator_first_name . ' ' . $payment->creator_last_name); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo base_url('vendor_payments/view/' . $payment->id); ?>" class="btn btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo base_url('vendor_payments/edit/' . $payment->id); ?>" class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($payment->status === 'Pending'): ?>
                                                    <button type="button" class="btn btn-outline-success" title="Approve" 
                                                            onclick="updatePaymentStatus(<?php echo $payment->id; ?>, 'Approved')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" title="Reject" 
                                                            onclick="updatePaymentStatus(<?php echo $payment->id; ?>, 'Declined')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($this->session->userdata('role') === 'admin'): ?>
                                                    <button type="button" class="btn btn-outline-danger" title="Delete" 
                                                            onclick="deletePayment(<?php echo $payment->id; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (!empty($pagination)): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo $pagination; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deletePayment(paymentId) {
    if (confirm('Are you sure you want to delete this payment record? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('vendor_payments/delete/'); ?>' + paymentId;
    }
}

function updatePaymentStatus(paymentId, status) {
    const action = status === 'Approved' ? 'approve' : 'reject';
    const confirmMessage = `Are you sure you want to ${action} this payment?`;
    
    if (confirm(confirmMessage)) {
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        fetch('<?php echo base_url('vendor_payments/update_status'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `payment_id=${paymentId}&status=${status}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert(`Payment ${action}d successfully!`);
                // Reload the page to reflect changes
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to update payment status'));
                // Restore button
                button.innerHTML = originalContent;
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the payment status');
            // Restore button
            button.innerHTML = originalContent;
            button.disabled = false;
        });
    }
}
</script> 