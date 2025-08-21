<div class="d-flex">
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-money-bill-wave me-2"></i>Vendor Payments
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('vendor_payments/add'); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Payment
                    </a>
                    <?php if ($this->session->userdata('role') === 'admin'): ?>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importPaymentsModal">
                            <i class="fas fa-upload me-1"></i>Import
                        </button>
                    <?php endif; ?>
                    <a href="<?php echo base_url('vendor_payments/export'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download me-1"></i>Export CSV
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

        <?php if ($this->session->flashdata('import_errors')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Import completed with errors:</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-warning" type="button" data-bs-toggle="collapse" data-bs-target="#importErrors">
                        View Details (<?php echo count($this->session->flashdata('import_errors')); ?> errors)
                    </button>
                    <div class="collapse mt-2" id="importErrors">
                        <div class="card card-body bg-light">
                            <ul class="mb-0 small">
                                <?php foreach ($this->session->flashdata('import_errors') as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Overall Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-primary fw-bold small mb-1">Total Payments</div>
                                <div class="h4 mb-0 fw-bold"><?php echo $overall_summary->total_payments ?? 0; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-warning fw-bold small mb-1">Pending</div>
                                <div class="h4 mb-0 fw-bold"><?php echo $overall_summary->pending_count ?? 0; ?></div>
                                <div class="text-warning small">$<?php echo number_format($overall_summary->pending_amount ?? 0, 2); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-success fw-bold small mb-1">Approved</div>
                                <div class="h4 mb-0 fw-bold"><?php echo $overall_summary->approved_count ?? 0; ?></div>
                                <div class="text-success small">$<?php echo number_format($overall_summary->approved_amount ?? 0, 2); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-danger fw-bold small mb-1">Declined</div>
                                <div class="h4 mb-0 fw-bold"><?php echo $overall_summary->declined_count ?? 0; ?></div>
                                <div class="text-danger small">$<?php echo number_format($overall_summary->declined_amount ?? 0, 2); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo base_url('vendor_payments'); ?>" class="row g-3">
                    <div class="col-md-2">
                        <label for="vendor" class="form-label">Vendor</label>
                        <select class="form-select" id="vendor" name="vendor">
                            <option value="">All Vendors</option>
                            <?php foreach ($vendors as $vendor): ?>
                                <option value="<?php echo $vendor->vendor; ?>" <?php echo ($filters['vendor'] == $vendor->vendor) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($vendor->vendor); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
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
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="<?php echo base_url('vendor_payments'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vendor Summary Table -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Vendor Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Vendor</th>
                                <th>Total Payments</th>
                                <th>Pending</th>
                                <th>Approved</th>
                                <th>Declined</th>
                                <th>Pending Amount</th>
                                <th>Approved Amount</th>
                                <th>Declined Amount</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($vendor_summary)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No vendor payments found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($vendor_summary as $summary): ?>
                                    <tr>
                                        <td>
                                            <strong>
                                                <a href="<?php echo base_url('vendors/profile/' . $summary->vendor_id); ?>" 
                                                   class="text-decoration-none" 
                                                   title="View Vendor Profile">
                                                    <?php echo htmlspecialchars($summary->vendor); ?>
                                                    <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.8em;"></i>
                                                </a>
                                            </strong>
                                        </td>
                                        <td><?php echo $summary->total_payments; ?></td>
                                        <td>
                                            <span class="badge bg-warning"><?php echo $summary->pending_count ?? 0; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success"><?php echo $summary->approved_count; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger"><?php echo $summary->declined_count; ?></span>
                                        </td>
                                        <td class="text-warning">$<?php echo number_format($summary->pending_amount ?? 0, 2); ?></td>
                                        <td class="text-success">$<?php echo number_format($summary->approved_amount, 2); ?></td>
                                        <td class="text-danger">$<?php echo number_format($summary->declined_amount, 2); ?></td>
                                        <td class="fw-bold">$<?php echo number_format($summary->total_amount, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Payment Records</h5>
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
                                <th>Screenshot</th>
                                <th>Status</th>
                                <th>Vendor</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($payments)): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No payment records found</td>
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
                                            <?php if ($payment->screenshot): ?>
                                                <?php if (pathinfo($payment->screenshot, PATHINFO_EXTENSION) === 'pdf'): ?>
                                                    <a href="<?php echo base_url($payment->screenshot); ?>" target="_blank" 
                                                       class="btn btn-sm btn-outline-primary" title="View PDF">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            onclick="viewScreenshot('<?php echo base_url($payment->screenshot); ?>')" 
                                                            title="View Screenshot">
                                                        <i class="fas fa-image"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted small">No screenshot</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($payment->status === 'Pending'): ?>
                                                <span class="badge bg-warning"><?php echo $payment->status; ?></span>
                                            <?php elseif ($payment->status === 'Approved'): ?>
                                                <span class="badge bg-success"><?php echo $payment->status; ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><?php echo $payment->status; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($payment->vendor_id): ?>
                                                <a href="<?php echo base_url('vendors/profile/' . $payment->vendor_id); ?>" 
                                                   class="text-decoration-none" 
                                                   title="View Vendor Profile">
                                                    <?php echo htmlspecialchars($payment->vendor_name ?? 'Unknown Vendor'); ?>
                                                    <i class="fas fa-external-link-alt ms-1 text-muted" style="font-size: 0.8em;"></i>
                                                </a>
                                            <?php else: ?>
                                                <?php echo htmlspecialchars($payment->vendor_name ?? 'Unknown Vendor'); ?>
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

<!-- Import Payments Modal -->
<div class="modal fade" id="importPaymentsModal" tabindex="-1" aria-labelledby="importPaymentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importPaymentsModalLabel">
                    <i class="fas fa-upload me-2"></i>Import Vendor Payments
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <form action="<?php echo base_url('vendor_payments/import'); ?>" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="csv_file" class="form-label">CSV File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                                <div class="form-text">Select a CSV file with vendor payment data</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Import Options</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="skip_duplicates" name="skip_duplicates" value="1" checked>
                                    <label class="form-check-label" for="skip_duplicates">
                                        Skip duplicate payments (based on date, sender, receiver, and amount)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="set_pending" name="set_pending" value="1">
                                    <label class="form-check-label" for="set_pending">
                                        Set all imported payments to "Pending" status
                                    </label>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo base_url('vendor_payments/download_template'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-download me-1"></i>Download Template
                                </a>
                                <div>
                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload me-1"></i>Import Payments
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-1"></i>CSV Format</h6>
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-2">Required columns:</p>
                                <ul class="small text-muted">
                                    <li><strong>date</strong> - Payment date (YYYY-MM-DD)</li>
                                    <li><strong>vendor_name</strong> - Vendor name (must exist in system)</li>
                                    <li><strong>sender</strong> - Sender name</li>
                                    <li><strong>receiver</strong> - Receiver name</li>
                                    <li><strong>mode</strong> - Payment mode (Zelle, Cash App, Venmo)</li>
                                    <li><strong>amount</strong> - Payment amount (numeric)</li>
                                    <li><strong>status</strong> - Status (Pending, Approved, Declined)</li>
                                </ul>
                                <p class="small text-muted mb-0">Optional columns:</p>
                                <ul class="small text-muted">
                                    <li><strong>notes</strong> - Additional notes</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Screenshot Modal -->
<div class="modal fade" id="screenshotModal" tabindex="-1" aria-labelledby="screenshotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="screenshotModalLabel">
                    <i class="fas fa-image me-2"></i>Payment Screenshot
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="screenshotImage" src="" alt="Payment Screenshot" class="img-fluid" style="max-height: 70vh;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="downloadScreenshot" href="" download class="btn btn-primary">
                    <i class="fas fa-download me-1"></i>Download
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function viewScreenshot(imageUrl) {
    document.getElementById('screenshotImage').src = imageUrl;
    document.getElementById('downloadScreenshot').href = imageUrl;
    new bootstrap.Modal(document.getElementById('screenshotModal')).show();
}

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