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
                <i class="fas fa-shopping-cart me-2"></i>Manage Orders
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/create_order'); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Order
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-primary fw-bold small mb-1">
                                    Total Orders
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count($orders); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-muted"></i>
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
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($orders, function($o) { return $o->status === 'pending'; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-info fw-bold small mb-1">
                                    In Transit
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($orders, function($o) { return in_array($o->status, ['processing', 'shipped']); })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck fa-2x text-muted"></i>
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
                                    Delivered
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($orders, function($o) { return $o->status === 'delivered'; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-muted"></i>
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
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by order ID or customer name...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sortBy" class="form-label">Sort By</label>
                        <select class="form-select" id="sortBy">
                            <option value="date">Date</option>
                            <option value="amount">Amount</option>
                            <option value="name">Customer Name</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="filterOrders()">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Orders List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Product</th>
                                <th>Brand</th>
                                <th>Strength</th>
                                <th>Variant</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Tracking</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr data-name="<?php echo htmlspecialchars($order->first_name . ' ' . $order->last_name); ?>" 
                                data-status="<?php echo $order->status; ?>" 
                                data-amount="<?php echo $order->total_amount; ?>" 
                                data-date="<?php echo strtotime($order->created_at); ?>">
                                <td><?php echo date('M j, Y H:i', strtotime($order->created_at)); ?></td>
                                <td>#<?php echo $order->id; ?></td>
                                <td>
                                    <a href="<?php echo base_url('admin/view_customer/' . $order->user_id); ?>" 
                                       class="text-decoration-none fw-bold text-primary">
                                        <?php echo htmlspecialchars($order->first_name . ' ' . $order->last_name); ?>
                                    </a>
                                </td>
                                <td><?php echo $order->product_name; ?></td>
                                <td><?php echo $order->brand ? htmlspecialchars($order->brand) : '-'; ?></td>
                                <td><?php echo $order->strength ? htmlspecialchars($order->strength) : '-'; ?></td>
                                <td><?php echo $order->product_quantity; ?> pills</td>
                                <td>
                                    <span class="fw-bold text-success">
                                        $<?php echo number_format($order->total_amount, 2); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusColor = isset($statusColors[$order->status]) ? $statusColors[$order->status] : 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $statusColor; ?>">
                                        <?php echo ucfirst($order->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($order->tracking_number): ?>
                                        <span class="badge bg-info"><?php echo $order->tracking_number; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo base_url('admin/view_order/' . $order->id); ?>" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($order->status === 'pending'): ?>
                                            <button type="button" class="btn btn-outline-info" 
                                                    onclick="updateStatus(<?php echo $order->id; ?>, 'processing')" title="Mark Processing">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                        <?php elseif ($order->status === 'processing'): ?>
                                            <button type="button" class="btn btn-outline-primary" 
                                                    onclick="updateStatus(<?php echo $order->id; ?>, 'shipped')" title="Mark Shipped">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        <?php elseif ($order->status === 'shipped'): ?>
                                            <button type="button" class="btn btn-outline-success" 
                                                    onclick="updateStatus(<?php echo $order->id; ?>, 'delivered')" title="Mark Delivered">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                        <?php if (in_array($order->status, ['pending', 'processing'])): ?>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="updateStatus(<?php echo $order->id; ?>, 'cancelled')" title="Cancel Order">
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
const table = document.getElementById('ordersTable');
const tbody = table.querySelector('tbody');
const rows = Array.from(tbody.querySelectorAll('tr'));

function filterOrders() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusFilterValue = statusFilter.value;
    let filteredRows = rows.filter(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const status = row.getAttribute('data-status');
        const matchesSearch = name.includes(searchTerm);
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

function updateStatus(orderId, status) {
    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
    if (confirm(`Are you sure you want to mark this order as ${statusText}?`)) {
        window.location.href = '<?php echo base_url('admin/update_order_status/'); ?>' + orderId + '/' + status;
    }
}

// Event listeners
searchInput.addEventListener('input', filterOrders);
statusFilter.addEventListener('change', filterOrders);
sortBy.addEventListener('change', filterOrders);
</script>

<?php $this->load->view('admin/includes/footer'); ?> 