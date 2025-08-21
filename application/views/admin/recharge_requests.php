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
    
    .table-responsive {
        width: 100% !important;
    }
    
    .table {
        width: 100% !important;
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
                <i class="fas fa-money-bill-wave me-2"></i>Recharge Requests
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-primary fw-bold small mb-1">
                                    Total Requests
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count($recharge_requests); ?></div>
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
                                <div class="text-uppercase text-warning fw-bold small mb-1">
                                    Pending
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($recharge_requests, function($r) { return $r->status === 'pending'; })); ?></div>
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
                                <div class="text-uppercase text-success fw-bold small mb-1">
                                    Approved
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($recharge_requests, function($r) { return $r->status === 'approved'; })); ?></div>
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
                                <div class="text-uppercase text-danger fw-bold small mb-1">
                                    Rejected
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($recharge_requests, function($r) { return $r->status === 'rejected'; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="searchInput" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by user name or email...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sortBy" class="form-label">Sort By</label>
                        <select class="form-select" id="sortBy">
                            <option value="date">Date</option>
                            <option value="amount">Amount</option>
                            <option value="name">User Name</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="filterRequests()">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recharge Requests Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recharge Requests List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="requestsTable">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recharge_requests as $request): ?>
                            <tr data-name="<?php echo htmlspecialchars($request->first_name . ' ' . $request->last_name); ?>" 
                                data-email="<?php echo htmlspecialchars($request->email); ?>" 
                                data-status="<?php echo $request->status; ?>" 
                                data-amount="<?php echo $request->amount; ?>" 
                                data-date="<?php echo strtotime($request->created_at); ?>">
                                <td>#<?php echo $request->id; ?></td>
                                <td>
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($request->first_name . ' ' . $request->last_name); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($request->email); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        $<?php echo number_format($request->amount, 2); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo ucwords(str_replace('_', ' ', $request->payment_mode)); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($request->status === 'pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php elseif ($request->status === 'approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($request->created_at)); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo base_url('admin/view_recharge/' . $request->id); ?>" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($request->status === 'pending'): ?>
                                            <button type="button" class="btn btn-outline-success" 
                                                    onclick="approveRequest(<?php echo $request->id; ?>)" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="rejectRequest(<?php echo $request->id; ?>)" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const sortBy = document.getElementById('sortBy');
const table = document.getElementById('requestsTable');
const tbody = table.querySelector('tbody');
const rows = Array.from(tbody.querySelectorAll('tr'));

function filterRequests() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusFilterValue = statusFilter.value;
    let filteredRows = rows.filter(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const email = row.getAttribute('data-email').toLowerCase();
        const status = row.getAttribute('data-status');
        const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
        const matchesStatus = !statusFilterValue || statusFilterValue === 'all' || status === statusFilterValue;
        return matchesSearch && matchesStatus;
    });

    // Sort rows
    const sortValue = sortBy.value;
    filteredRows.sort((a, b) => {
        if (sortValue === 'name') {
            return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
        } else if (sortValue === 'amount') {
            return parseFloat(b.getAttribute('data-amount')) - parseFloat(a.getAttribute('data-amount'));
        } else if (sortValue === 'date') {
            return b.getAttribute('data-date') - a.getAttribute('data-date');
        }
        return 0;
    });

    tbody.innerHTML = '';
    filteredRows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

function approveRequest(requestId) {
    if (confirm('Are you sure you want to approve this recharge request?')) {
        window.location.href = '<?php echo base_url('admin/approve_recharge/'); ?>' + requestId;
    }
}

function rejectRequest(requestId) {
    if (confirm('Are you sure you want to reject this recharge request?')) {
        window.location.href = '<?php echo base_url('admin/reject_recharge/'); ?>' + requestId;
    }
}

// Event listeners
searchInput.addEventListener('input', filterRequests);
statusFilter.addEventListener('change', filterRequests);
sortBy.addEventListener('change', filterRequests);
</script>

<?php $this->load->view('admin/includes/footer'); ?> 