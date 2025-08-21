<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-credit-card me-2 text-info"></i>Recharge Requests
                </h1>
                <p class="text-muted mb-0">Manage customer wallet recharge requests.</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $pending_requests; ?></h4>
                                <small>Pending</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $approved_requests; ?></h4>
                                <small>Approved</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $rejected_requests; ?></h4>
                                <small>Rejected</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">$<?php echo number_format($request_summary->total_approved_amount, 2); ?></h4>
                                <small>Total Approved Amount</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search requests...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="allRequests">All</button>
                    <button type="button" class="btn btn-outline-warning" id="pendingRequests">Pending</button>
                    <button type="button" class="btn btn-outline-success" id="approvedRequests">Approved</button>
                    <button type="button" class="btn btn-outline-danger" id="rejectedRequests">Rejected</button>
                </div>
            </div>
        </div>

        <!-- Recharge Requests Table -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($requests)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Request #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Requested Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo base_url('staff/view_recharge/' . $request->id); ?>" class="text-decoration-none">
                                            #<?php echo $request->id; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('staff/view_customer/' . $request->user_id); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($request->first_name . ' ' . $request->last_name); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">$<?php echo number_format($request->amount, 2); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($request->payment_method ?? 'Not specified'); ?></td>
                                    <td>
                                        <?php
                                        $status_colors = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger'
                                        ];
                                        $color = isset($status_colors[$request->status]) ? $status_colors[$request->status] : 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $color; ?>">
                                            <?php echo ucfirst($request->status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($request->created_at)); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('staff/view_recharge/' . $request->id); ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No recharge requests found</h5>
                        <p class="text-muted">There are no recharge requests in the system yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    
    searchBtn.addEventListener('click', function() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            window.location.href = '<?php echo base_url('staff/recharge_requests'); ?>?search=' + encodeURIComponent(searchTerm);
        }
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
    
    // Filter buttons
    document.getElementById('allRequests').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/recharge_requests'); ?>';
    });
    
    document.getElementById('pendingRequests').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/recharge_requests'); ?>?status=pending';
    });
    
    document.getElementById('approvedRequests').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/recharge_requests'); ?>?status=approved';
    });
    
    document.getElementById('rejectedRequests').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/recharge_requests'); ?>?status=rejected';
    });
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 