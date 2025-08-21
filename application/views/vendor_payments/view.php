<div class="d-flex">
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-eye me-2"></i>View Vendor Payment
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('vendor_payments'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to List
                    </a>
                    <a href="<?php echo base_url('vendor_payments/edit/' . $payment->id); ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deletePayment(<?php echo $payment->id; ?>)">
                        <i class="fas fa-trash me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Payment Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold" style="width: 40%;">Date:</td>
                                        <td><?php echo date('F j, Y', strtotime($payment->date)); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Vendor:</td>
                                        <td><?php echo htmlspecialchars($payment->vendor_name ?? 'Unknown Vendor'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Sender:</td>
                                        <td><?php echo htmlspecialchars($payment->sender); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Receiver:</td>
                                        <td><?php echo htmlspecialchars($payment->receiver); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold" style="width: 40%;">Payment Mode:</td>
                                        <td>
                                            <span class="badge bg-info"><?php echo $payment->mode; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Amount:</td>
                                        <td class="h5 text-success fw-bold">$<?php echo number_format($payment->amount, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status:</td>
                                        <td>
                                            <?php if ($payment->status === 'Pending'): ?>
                                                <span class="badge bg-warning fs-6"><?php echo $payment->status; ?></span>
                                            <?php elseif ($payment->status === 'Approved'): ?>
                                                <span class="badge bg-success fs-6"><?php echo $payment->status; ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger fs-6"><?php echo $payment->status; ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Created By:</td>
                                        <td><?php echo htmlspecialchars($payment->creator_first_name . ' ' . $payment->creator_last_name); ?></td>
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
                            <i class="fas fa-info-circle me-2"></i>
                            Record Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 40%;">Record ID:</td>
                                <td>#<?php echo $payment->id; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Created:</td>
                                <td><?php echo date('M j, Y g:i A', strtotime($payment->created_at)); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Updated:</td>
                                <td><?php echo date('M j, Y g:i A', strtotime($payment->updated_at)); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <!-- Screenshot Section -->
                <?php if ($payment->screenshot): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-image me-2"></i>
                            Payment Screenshot
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if (pathinfo($payment->screenshot, PATHINFO_EXTENSION) === 'pdf'): ?>
                            <div class="mb-3">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                <p class="text-muted">PDF Document</p>
                            </div>
                            <a href="<?php echo base_url($payment->screenshot); ?>" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt me-1"></i>View PDF
                            </a>
                        <?php else: ?>
                            <img src="<?php echo base_url($payment->screenshot); ?>" alt="Payment Screenshot" 
                                 class="img-fluid rounded mb-3" style="max-height: 300px;">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" 
                                        onclick="viewScreenshot('<?php echo base_url($payment->screenshot); ?>')">
                                    <i class="fas fa-expand me-1"></i>View Full Size
                                </button>
                                <a href="<?php echo base_url($payment->screenshot); ?>" download class="btn btn-outline-primary">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Quick Actions -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo base_url('vendor_payments/edit/' . $payment->id); ?>" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>Edit Payment
                            </a>
                            <?php if ($this->session->userdata('role') === 'admin'): ?>
                                <button type="button" class="btn btn-danger" onclick="deletePayment(<?php echo $payment->id; ?>)">
                                    <i class="fas fa-trash me-1"></i>Delete Payment
                                </button>
                            <?php endif; ?>
                            <a href="<?php echo base_url('vendor_payments'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-1"></i>View All Payments
                            </a>
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
</script> 