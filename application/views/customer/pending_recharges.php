<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-clock me-2"></i>Pending Recharge Requests
                    </h1>
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

                <!-- Current Balance -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title text-primary mb-1">Current Balance</h5>
                                        <h3 class="mb-0 <?php echo ($wallet && $wallet->balance < 0) ? 'text-danger' : ''; ?>">
                                            $<?php echo number_format($wallet ? $wallet->balance : 0, 2); ?>
                                        </h3>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-wallet fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-warning">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="card-title text-warning mb-1">Pending Requests</h5>
                                        <h3 class="mb-0"><?php echo count($pending_recharges); ?></h3>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests List -->
                <?php if (!empty($pending_recharges)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Your Pending Requests
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Request ID</th>
                                            <th>Amount</th>
                                            <th>Payment Method</th>
                                            <th>Transaction ID</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pending_recharges as $request): ?>
                                        <tr>
                                            <td>
                                                <strong>#<?php echo $request->id; ?></strong>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">$<?php echo number_format($request->amount, 2); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($request->payment_mode); ?></span>
                                            </td>
                                            <td>
                                                <code><?php echo htmlspecialchars($request->transaction_id); ?></code>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Pending Review</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo date('M j, Y H:i', strtotime($request->created_at)); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#requestModal<?php echo $request->id; ?>">
                                                    <i class="fas fa-eye"></i> View Details
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Request Detail Modals -->
                    <?php foreach ($pending_recharges as $request): ?>
                    <div class="modal fade" id="requestModal<?php echo $request->id; ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Recharge Request #<?php echo $request->id; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Request Details</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Amount:</strong></td>
                                                    <td>$<?php echo number_format($request->amount, 2); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Payment Method:</strong></td>
                                                    <td><?php echo htmlspecialchars($request->payment_mode); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Transaction ID:</strong></td>
                                                    <td><code><?php echo htmlspecialchars($request->transaction_id); ?></code></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status:</strong></td>
                                                    <td><span class="badge bg-warning">Pending Review</span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Submitted:</strong></td>
                                                    <td><?php echo date('M j, Y H:i', strtotime($request->created_at)); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Notes</h6>
                                            <p class="text-muted">
                                                <?php echo $request->notes ? htmlspecialchars($request->notes) : 'No additional notes provided.'; ?>
                                            </p>
                                            
                                            <?php if ($request->screenshot): ?>
                                                <h6>Screenshot</h6>
                                                <img src="<?php echo base_url($request->screenshot); ?>" 
                                                     class="img-fluid rounded" 
                                                     alt="Payment Screenshot"
                                                     style="max-height: 200px;">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="empty-state">
                                <i class="fas fa-clock"></i>
                                <h4>No Pending Requests</h4>
                                <p class="text-muted">You don't have any pending recharge requests.</p>
                                <a href="<?php echo base_url('customer/recharge'); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i>Request New Recharge
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

<?php $this->load->view('customer/common/footer'); ?> 