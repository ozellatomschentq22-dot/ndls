<?php $this->load->view('admin/includes/header'); ?>

<style>
    .main-content {
        width: calc(100% - 280px) !important;
        max-width: none !important;
        margin-left: 280px !important;
    }
    
    .card {
        width: 100% !important;
        max-width: none !important;
    }
    
    @media (max-width: 768px) {
        .main-content {
            width: 100% !important;
            margin-left: 0 !important;
        }
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-money-bill-wave me-2"></i>View Recharge Request
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/recharge_requests'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Requests
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

        <!-- Recharge Request Details -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-money-bill-wave me-2 text-primary"></i>Recharge Request Details
                            </h5>
                            <span class="badge bg-<?php echo $request->status === 'pending' ? 'warning' : ($request->status === 'approved' ? 'success' : 'danger'); ?> fs-6">
                                <?php echo ucfirst($request->status); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Request ID</label>
                                    <p class="mb-0">#<?php echo $request->id; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Amount</label>
                                    <p class="mb-0 text-primary fs-5">$<?php echo number_format($request->amount, 2); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Payment Method</label>
                                    <p class="mb-0">
                                        <i class="fas fa-credit-card me-2"></i>
                                        <?php echo ucwords(str_replace('_', ' ', $request->payment_mode)); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Transaction ID</label>
                                    <p class="mb-0"><?php echo $request->transaction_id ?: 'Not provided'; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes</label>
                            <div class="border rounded p-3 bg-light">
                                <?php echo $request->notes ? nl2br(htmlspecialchars($request->notes)) : 'No notes provided'; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Request Date</label>
                                    <p class="mb-0"><?php echo date('M j, Y H:i', strtotime($request->created_at)); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Last Updated</label>
                                    <p class="mb-0"><?php echo date('M j, Y H:i', strtotime($request->updated_at)); ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if ($request->status === 'pending'): ?>
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3">Actions</h6>
                            <div class="d-flex gap-2">
                                <a href="<?php echo base_url('admin/approve_recharge/' . $request->id); ?>" 
                                   class="btn btn-success" 
                                   onclick="return confirm('Are you sure you want to approve this recharge request?')">
                                    <i class="fas fa-check me-1"></i>Approve
                                </a>
                                <a href="<?php echo base_url('admin/reject_recharge/' . $request->id); ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Are you sure you want to reject this recharge request?')">
                                    <i class="fas fa-times me-1"></i>Reject
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer Name</label>
                            <p class="mb-0"><?php echo $customer->first_name . ' ' . $customer->last_name; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="mb-0"><?php echo $customer->email; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="mb-0"><?php echo $customer->phone ?: 'Not provided'; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Balance</label>
                            <p class="mb-0 text-success fs-5">$<?php echo number_format($wallet->balance, 2); ?></p>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo base_url('admin/view_customer/' . $customer->id); ?>" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>View Customer Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Details -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>Payment Method Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Method</label>
                            <p class="mb-0">
                                <i class="<?php echo $payment_method->icon ?? 'fas fa-credit-card'; ?> me-2"></i>
                                <?php echo $payment_method->display_name ?? ucwords(str_replace('_', ' ', $request->payment_mode)); ?>
                            </p>
                        </div>
                        <?php if (isset($payment_method->instructions) && $payment_method->instructions): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Instructions</label>
                            <div class="small text-muted">
                                <?php echo nl2br(htmlspecialchars($payment_method->instructions)); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 