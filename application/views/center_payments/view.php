<?php $this->load->view('admin/includes/header'); ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-money-bill-wave me-2"></i>Payment Details
                </h1>
                <p class="text-muted">View detailed information about payment #<?php echo $payment->id; ?></p>
            </div>
            <div>
                <a href="<?php echo base_url('center_payments'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Payments
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Payment Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Payment ID:</strong></td>
                                        <td><code><?php echo $payment->id; ?></code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Center:</strong></td>
                                        <td><strong><?php echo htmlspecialchars($payment->center_name); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Amount Paid:</strong></td>
                                        <td><strong class="text-success">₹<?php echo number_format($payment->amount_paid, 2); ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Payment Date:</strong></td>
                                        <td><?php echo date('M j, Y', strtotime($payment->payment_date)); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Method:</strong></td>
                                        <td><?php echo htmlspecialchars($payment->payment_method); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
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
                                    </tr>
                                    <tr>
                                        <td><strong>Reference Number:</strong></td>
                                        <td>
                                            <?php if ($payment->reference_number): ?>
                                                <code><?php echo htmlspecialchars($payment->reference_number); ?></code>
                                            <?php else: ?>
                                                <span class="text-muted">Not provided</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($payment->created_at)); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <?php if ($payment->notes): ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><strong>Notes:</strong></h6>
                                <div class="alert alert-light">
                                    <?php echo nl2br(htmlspecialchars($payment->notes)); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Center Outstanding Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Center Outstanding Information</h6>
                    </div>
                    <div class="card-body">
                        <?php 
                            $center_outstanding = $this->Center_payments_model->get_center_outstanding($payment->center_name);
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Center:</strong></td>
                                        <td><?php echo htmlspecialchars($payment->center_name); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Orders:</strong></td>
                                        <td><?php echo $center_outstanding['total_orders']; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Order Value:</strong></td>
                                        <td><strong class="text-primary">₹<?php echo number_format($center_outstanding['total_value'], 2); ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle me-1"></i>Payment Analysis</h6>
                                    <p class="mb-1"><strong>Amount Paid:</strong> ₹<?php echo number_format($payment->amount_paid, 2); ?></p>
                                    <p class="mb-1"><strong>Total Paid to Date:</strong> ₹<?php echo number_format($center_outstanding['total_paid'], 2); ?></p>
                                    <p class="mb-1"><strong>Outstanding Amount:</strong> 
                                        <span class="text-<?php echo $center_outstanding['outstanding'] > 0 ? 'danger' : ($center_outstanding['outstanding'] < 0 ? 'warning' : 'success'); ?>">
                                            ₹<?php echo number_format(abs($center_outstanding['outstanding']), 2); ?>
                                            <?php if ($center_outstanding['outstanding'] < 0): ?>
                                                (Overpaid)
                                            <?php elseif ($center_outstanding['outstanding'] > 0): ?>
                                                (Due)
                                            <?php else: ?>
                                                (Paid)
                                            <?php endif; ?>
                                        </span>
                                    </p>
                                    <p class="mb-0"><strong>Payment Coverage:</strong> <?php echo $center_outstanding['total_value'] > 0 ? number_format(($center_outstanding['total_paid'] / $center_outstanding['total_value']) * 100, 1) : 0; ?>%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo base_url('center_payments/edit/' . $payment->id); ?>" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>Edit Payment
                            </a>
                            
                            <a href="<?php echo base_url('centers/view/' . $payment->center_name); ?>" class="btn btn-info">
                                <i class="fas fa-building me-1"></i>View Center
                            </a>
                            
                            <button type="button" class="btn btn-danger" 
                                    onclick="deletePayment(<?php echo $payment->id; ?>, '<?php echo htmlspecialchars($payment->center_name); ?>')">
                                <i class="fas fa-trash me-1"></i>Delete Payment
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Payment History</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Payment Recorded</h6>
                                    <p class="timeline-text">Payment of ₹<?php echo number_format($payment->amount_paid, 2); ?> recorded</p>
                                    <small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($payment->created_at)); ?></small>
                                </div>
                            </div>
                            
                            <?php if ($payment->updated_at && $payment->updated_at !== $payment->created_at): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Payment Updated</h6>
                                    <p class="timeline-text">Payment information was last modified</p>
                                    <small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($payment->updated_at)); ?></small>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    background: #f8f9fc;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #4e73df;
}

.timeline-title {
    margin: 0 0 5px 0;
    font-weight: 600;
    color: #4e73df;
}

.timeline-text {
    margin: 0 0 5px 0;
    color: #5a5c69;
}

.timeline-content small {
    font-size: 0.8em;
}
</style>

<script>
function deletePayment(paymentId, centerName) {
    const amount = '<?php echo number_format($payment->amount_paid, 2); ?>';
    if (confirm('Are you sure you want to delete this payment of ₹' + amount + ' for center "' + centerName + '"? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('center_payments/delete/'); ?>' + paymentId;
    }
}
</script>

<?php $this->load->view('admin/includes/footer'); ?> 