<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-money-bill-wave me-2"></i>Center Payments
                </h1>
                <p class="text-muted">Track payments received from drop shipment centers</p>
            </div>
            <div>
                <a href="<?php echo base_url('center_payments/add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Record Payment
                </a>
                <a href="<?php echo base_url('center_payments/export_csv'); ?>" class="btn btn-success">
                    <i class="fas fa-download me-1"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Payments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $summary->total_payments ?? 0; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed Payments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $summary->completed_count ?? 0; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Payments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $summary->pending_count ?? 0; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Amount</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?php echo number_format($summary->total_amount_paid ?? 0, 2); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo base_url('center_payments'); ?>">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="center_name">Center</label>
                                <select name="center_name" id="center_name" class="form-control">
                                    <option value="">All Centers</option>
                                    <?php foreach ($centers as $center): ?>
                                        <option value="<?php echo $center->name; ?>" <?php echo (isset($filters['center_name']) && $filters['center_name'] === $center->name) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($center->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All Status</option>
                                    <?php foreach ($payment_statuses as $key => $value): ?>
                                        <option value="<?php echo $key; ?>" <?php echo (isset($filters['status']) && $filters['status'] === $key) ? 'selected' : ''; ?>>
                                            <?php echo $value; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-control">
                                    <option value="">All Methods</option>
                                    <?php foreach ($payment_methods as $key => $value): ?>
                                        <option value="<?php echo $key; ?>" <?php echo (isset($filters['payment_method']) && $filters['payment_method'] === $key) ? 'selected' : ''; ?>>
                                            <?php echo $value; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo $filters['date_from'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo $filters['date_to'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="search">Search</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Order #, Customer, Center..." value="<?php echo $filters['search'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <a href="<?php echo base_url('center_payments'); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Center Summary -->
        <?php if (!empty($center_summary)): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Center Payment Summary</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Center</th>
                                <th>Total Orders</th>
                                <th>Order Value</th>
                                <th>Total Paid</th>
                                <th>Outstanding</th>
                                <th>Payment Count</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($center_summary as $center): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($center->center_name); ?></strong></td>
                                <td><?php echo $center->total_orders; ?></td>
                                <td><strong class="text-primary">₹<?php echo number_format($center->total_value, 2); ?></strong></td>
                                <td><strong class="text-success">₹<?php echo number_format($center->total_paid, 2); ?></strong></td>
                                <td>
                                    <?php if ($center->outstanding > 0): ?>
                                        <strong class="text-danger">₹<?php echo number_format($center->outstanding, 2); ?></strong>
                                    <?php elseif ($center->outstanding < 0): ?>
                                        <strong class="text-warning">₹<?php echo number_format(abs($center->outstanding), 2); ?> (Overpaid)</strong>
                                    <?php else: ?>
                                        <strong class="text-success">₹0.00</strong>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?php echo $center->completed_count; ?></span>
                                    <span class="badge bg-warning"><?php echo $center->pending_count; ?></span>
                                    <span class="badge bg-danger"><?php echo $center->failed_count; ?></span>
                                </td>
                                <td>
                                    <?php if ($center->outstanding > 0): ?>
                                        <span class="badge bg-danger">Outstanding</span>
                                    <?php elseif ($center->outstanding < 0): ?>
                                        <span class="badge bg-warning">Overpaid</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Payments Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Payment Records</h6>
            </div>
            <div class="card-body">
                <?php if (empty($payments)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No payments found</h5>
                        <p class="text-muted">No payment records match your current filters.</p>
                        <a href="<?php echo base_url('center_payments/add'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Record First Payment
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Center</th>
                                    <th>Amount</th>
                                    <th>Payment Date</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Reference</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><code><?php echo $payment->id; ?></code></td>
                                    <td><strong><?php echo htmlspecialchars($payment->center_name); ?></strong></td>
                                    <td>
                                        <strong class="text-success">₹<?php echo number_format($payment->amount_paid, 2); ?></strong>
                                    </td>
                                    <td>
                                        <div><?php echo date('M j, Y', strtotime($payment->payment_date)); ?></div>
                                        <small class="text-muted"><?php echo date('g:i A', strtotime($payment->created_at)); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($payment->payment_method); ?></td>
                                    <td>
                                        <?php if ($payment->status === 'Completed'): ?>
                                            <span class="badge bg-success"><?php echo $payment->status; ?></span>
                                        <?php elseif ($payment->status === 'Pending'): ?>
                                            <span class="badge bg-warning"><?php echo $payment->status; ?></span>
                                        <?php elseif ($payment->status === 'Failed'): ?>
                                            <span class="badge bg-danger"><?php echo $payment->status; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-info"><?php echo $payment->status; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($payment->reference_number): ?>
                                            <code><?php echo htmlspecialchars($payment->reference_number); ?></code>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($payment->creator_first_name . ' ' . $payment->creator_last_name); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('center_payments/view/' . $payment->id); ?>" class="btn btn-outline-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo base_url('center_payments/edit/' . $payment->id); ?>" class="btn btn-outline-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Delete" 
                                                    onclick="deletePayment(<?php echo $payment->id; ?>, '<?php echo htmlspecialchars($payment->center_name); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($pagination)): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo $pagination; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deletePayment(paymentId, centerName) {
    if (confirm('Are you sure you want to delete this payment for center "' + centerName + '"? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('center_payments/delete/'); ?>' + paymentId;
    }
}
</script> 