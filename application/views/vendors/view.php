<div class="d-flex">
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-user me-2"></i>Vendor Details: <?php echo htmlspecialchars($vendor->name); ?>
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('vendors'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Vendors
                    </a>
                    <a href="<?php echo base_url('vendors/profile/' . $vendor->id); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-chart-bar me-1"></i>View Profile
                    </a>
                    <a href="<?php echo base_url('vendors/edit/' . $vendor->id); ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit me-1"></i>Edit Vendor
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
                            <i class="fas fa-calculator me-2"></i>
                            Payout Calculator
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="amount_usd" class="form-label">Amount (USD)</label>
                            <input type="number" class="form-control" id="amount_usd" placeholder="Enter USD amount" step="0.01" min="0">
                        </div>
                        <button type="button" class="btn btn-primary w-100 mb-3" onclick="calculatePayout()">
                            <i class="fas fa-calculator me-1"></i>Calculate Payout
                        </button>
                        <div id="payout_result" class="alert alert-info" style="display: none;">
                            <h6 class="alert-heading">Payout Calculation:</h6>
                            <div id="payout_details"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('vendors/profile/' . $vendor->id); ?>" class="btn btn-primary w-100">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    View Payment History
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('vendor_payments/add'); ?>" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>
                                    Add Payment
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('vendors/edit/' . $vendor->id); ?>" class="btn btn-warning w-100">
                                    <i class="fas fa-edit me-2"></i>
                                    Edit Vendor
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-danger w-100" onclick="deleteVendor(<?php echo $vendor->id; ?>)">
                                    <i class="fas fa-trash me-2"></i>
                                    Delete Vendor
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteVendor(vendorId) {
    if (confirm('Are you sure you want to delete this vendor? This action cannot be undone and will also delete all associated payment records.')) {
        window.location.href = '<?php echo base_url('vendors/delete/'); ?>' + vendorId;
    }
}

function calculatePayout() {
    const amountUsd = document.getElementById('amount_usd').value;
    const vendorId = <?php echo $vendor->id; ?>;
    
    if (!amountUsd || amountUsd <= 0) {
        alert('Please enter a valid USD amount');
        return;
    }
    
    fetch('<?php echo base_url('vendors/calculate_payout'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `vendor_id=${vendorId}&amount_usd=${amountUsd}`
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.getElementById('payout_result');
        const detailsDiv = document.getElementById('payout_details');
        
        if (data.error) {
            detailsDiv.innerHTML = `<div class="text-danger">${data.error}</div>`;
        } else {
            detailsDiv.innerHTML = `
                <div class="row">
                    <div class="col-6">
                        <strong>Vendor Payout:</strong><br>
                        ₹${data.vendor_payout.toFixed(2)}
                    </div>
                    <div class="col-6">
                        <strong>System Profit:</strong><br>
                        ₹${data.system_profit.toFixed(2)}
                    </div>
                </div>
                <hr>
                <div class="small text-muted">
                    <strong>Calculation:</strong><br>
                    Vendor: $${amountUsd} × ${data.vendor_rate}<br>
                    System: $${amountUsd} × ${data.system_rate}
                </div>
            `;
        }
        
        resultDiv.style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while calculating the payout');
    });
}
</script> 