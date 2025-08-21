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
                <i class="fas fa-users-cog me-2"></i>Admin & Staff Management
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/add_user'); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add User
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportUsers()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
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
                                    Total Users
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count($users); ?></div>
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
                                    Active Users
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($users, function($u) { return isset($u->status) && $u->status === 'active'; })); ?></div>
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
                                    Inactive Users
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($users, function($u) { return !isset($u->status) || $u->status !== 'active'; })); ?></div>
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
                                    Admin Users
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($users, function($u) { return isset($u->role) && $u->role === 'admin'; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-shield fa-2x text-muted"></i>
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
                        <label for="roleFilter" class="form-label">Role</label>
                        <select class="form-select" id="roleFilter">
                            <option value="all">All Roles</option>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="filterUsers()">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">User List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr data-name="<?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>" 
                                data-email="<?php echo htmlspecialchars($user->email); ?>" 
                                data-role="<?php echo isset($user->role) ? $user->role : ''; ?>" 
                                data-status="<?php echo isset($user->status) ? $user->status : ''; ?>" 
                                data-date="<?php echo strtotime($user->created_at); ?>">
                                <td>#<?php echo $user->id; ?></td>
                                <td><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></td>
                                <td><?php echo htmlspecialchars($user->email); ?></td>
                                <td><?php echo isset($user->phone) && !empty($user->phone) ? htmlspecialchars($user->phone) : 'N/A'; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo isset($user->role) && $user->role === 'admin' ? 'danger' : 'info'; ?>">
                                        <?php echo isset($user->role) ? ucfirst($user->role) : 'N/A'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (isset($user->status) && $user->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($user->created_at)); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo base_url('admin/edit_user/' . $user->id); ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url('admin/credit_wallet/' . $user->id); ?>" class="btn btn-outline-success" title="View Wallet">
                                            <i class="fas fa-wallet"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="deleteUser(<?php echo $user->id; ?>)" title="Delete">
                                            <i class="fas fa-trash"></i>
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

<script>
const searchInput = document.getElementById('searchInput');
const roleFilter = document.getElementById('roleFilter');
const statusFilter = document.getElementById('statusFilter');
const table = document.getElementById('usersTable');
const tbody = table.querySelector('tbody');
const rows = Array.from(tbody.querySelectorAll('tr'));

function filterUsers() {
    const searchTerm = searchInput.value.toLowerCase();
    const roleFilterValue = roleFilter.value;
    const statusFilterValue = statusFilter.value;
    let filteredRows = rows.filter(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const email = row.getAttribute('data-email').toLowerCase();
        const role = row.getAttribute('data-role');
        const status = row.getAttribute('data-status');
        const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
        const matchesRole = !roleFilterValue || roleFilterValue === 'all' || role === roleFilterValue;
        const matchesStatus = !statusFilterValue || statusFilterValue === 'all' || status === statusFilterValue;
        return matchesSearch && matchesRole && matchesStatus;
    });
    tbody.innerHTML = '';
    filteredRows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('admin/delete_user/'); ?>' + userId;
    }
}

function exportUsers() {
    // Implement export functionality
    alert('Export functionality will be implemented here.');
}

// Event listeners
searchInput.addEventListener('input', filterUsers);
roleFilter.addEventListener('change', filterUsers);
statusFilter.addEventListener('change', filterUsers);
</script>

<?php $this->load->view('admin/includes/footer'); ?> 