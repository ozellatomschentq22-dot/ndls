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
                <i class="fas fa-users me-2"></i>Customer Management
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importCustomersModal">
                        <i class="fas fa-upload me-1"></i>Import
                    </button>
                    <a href="<?php echo base_url('admin/export_customers'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download me-1"></i>Export
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-primary fw-bold small mb-1">
                                    Total Customers
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo $total_customers; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-muted"></i>
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
                                    Active Customers
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo $active_customers; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-muted"></i>
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
                                    Inactive Customers
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo $inactive_customers; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-times fa-2x text-muted"></i>
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
                                    Activity Rate
                                </div>
                                <div class="h4 mb-0 fw-bold">
                                    <?php echo $total_customers > 0 ? round(($active_customers / $total_customers) * 100, 1) : 0; ?>%
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="searchInput" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by name or email...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sortBy" class="form-label">Sort By</label>
                        <select class="form-select" id="sortBy">
                            <option value="name">Name</option>
                            <option value="email">Email</option>
                            <option value="date">Date</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="filterCustomers()">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Customer List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="customersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customers as $customer): ?>
                            <tr data-name="<?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?>" 
                                data-email="<?php echo htmlspecialchars($customer->email); ?>" 
                                data-status="<?php echo isset($customer->status) ? $customer->status : ''; ?>" 
                                data-date="<?php echo strtotime($customer->created_at); ?>">
                                <td>#<?php echo $customer->id; ?></td>
                                <td><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></td>
                                <td><?php echo htmlspecialchars($customer->email); ?></td>
                                <td><?php echo isset($customer->phone) && !empty($customer->phone) ? htmlspecialchars($customer->phone) : 'N/A'; ?></td>
                                <td>
                                    <?php if (isset($customer->status) && $customer->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($customer->created_at)); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo base_url('admin/view_customer/' . $customer->id); ?>" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url('admin/edit_customer/' . $customer->id); ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url('admin/credit_wallet/' . $customer->id); ?>" class="btn btn-outline-success" title="View Wallet">
                                            <i class="fas fa-wallet"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-<?php echo (isset($customer->status) && $customer->status === 'active') ? 'danger' : 'success'; ?>" 
                                                onclick="toggleCustomerStatus(<?php echo $customer->id; ?>)" 
                                                title="<?php echo (isset($customer->status) && $customer->status === 'active') ? 'Deactivate' : 'Activate'; ?>">
                                            <i class="fas fa-<?php echo (isset($customer->status) && $customer->status === 'active') ? 'ban' : 'check'; ?>"></i>
                                        </button>
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

<!-- Import Customers Modal -->
<div class="modal fade" id="importCustomersModal" tabindex="-1" aria-labelledby="importCustomersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importCustomersModalLabel">
                    <i class="fas fa-upload me-2"></i>Import Customers
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Upload CSV File</h6>
                        <form action="<?php echo base_url('admin/import_customers'); ?>" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="csv_file" class="form-label">Select CSV File</label>
                                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                                <div class="form-text">File must be in CSV format</div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="skip_duplicates" name="skip_duplicates" checked>
                                    <label class="form-check-label" for="skip_duplicates">
                                        Skip duplicate emails
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i>Import Customers
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">CSV Format Requirements</h6>
                        <div class="alert alert-info">
                            <h6>Required Columns:</h6>
                            <ul class="mb-2">
                                <li><strong>first_name</strong> - Customer's first name</li>
                                <li><strong>last_name</strong> - Customer's last name</li>
                                <li><strong>email</strong> - Customer's email address</li>
                                <li><strong>phone</strong> - Customer's phone number (optional)</li>
                                <li><strong>password</strong> - Customer's password (optional, default: welcome123)</li>
                            </ul>
                            <h6>Example:</h6>
                            <code>
                                first_name,last_name,email,phone,password<br>
                                John,Doe,john@example.com,555-1234,password123<br>
                                Jane,Smith,jane@example.com,555-5678,
                            </code>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo base_url('admin/download_customer_template'); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download me-1"></i>Download Template
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const sortBy = document.getElementById('sortBy');
const table = document.getElementById('customersTable');
const tbody = table.querySelector('tbody');
const rows = Array.from(tbody.querySelectorAll('tr'));

function filterCustomers() {
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
        } else if (sortValue === 'email') {
            return a.getAttribute('data-email').localeCompare(b.getAttribute('data-email'));
        } else if (sortValue === 'date') {
            return b.getAttribute('data-date') - a.getAttribute('data-date');
        }
        return 0;
    });

    tbody.innerHTML = '';
    filteredRows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

function toggleCustomerStatus(customerId) {
    if (confirm('Are you sure you want to change this customer\'s status?')) {
        window.location.href = '<?php echo base_url('admin/toggle_customer/'); ?>' + customerId;
    }
}

// Event listeners
searchInput.addEventListener('input', filterCustomers);
statusFilter.addEventListener('change', filterCustomers);
sortBy.addEventListener('change', filterCustomers);
</script>

<?php $this->load->view('admin/includes/footer'); ?> 