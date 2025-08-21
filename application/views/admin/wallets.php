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
                <i class="fas fa-wallet me-2"></i>Manage Wallets
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/credit_wallet'); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Credit User Wallet
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
                                    Total Wallets
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count($wallets); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-muted"></i>
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
                                    Total Balance
                                </div>
                                <div class="h4 mb-0 fw-bold">$<?php echo number_format(array_sum(array_column($wallets, 'balance')), 2); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-plus-circle fa-2x text-muted"></i>
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
                                    Positive Balance
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($wallets, function($w) { return $w->balance > 0; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-up fa-2x text-muted"></i>
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
                                    Negative Balance
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($wallets, function($w) { return $w->balance < 0; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-arrow-down fa-2x text-muted"></i>
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
                        <label for="balanceFilter" class="form-label">Balance Status</label>
                        <select class="form-select" id="balanceFilter">
                            <option value="all">All Balances</option>
                            <option value="positive">Positive</option>
                            <option value="negative">Negative</option>
                            <option value="zero">Zero</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sortBy" class="form-label">Sort By</label>
                        <select class="form-select" id="sortBy">
                            <option value="name">User Name</option>
                            <option value="balance">Balance</option>
                            <option value="date">Date</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="filterWallets()">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallets Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Wallets List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="walletsTable">
                        <thead>
                            <tr>
                                <th>Wallet ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Balance</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wallets as $wallet): ?>
                            <tr data-name="<?php echo htmlspecialchars($wallet->first_name . ' ' . $wallet->last_name); ?>" 
                                data-email="<?php echo htmlspecialchars($wallet->email); ?>" 
                                data-balance="<?php echo $wallet->balance; ?>" 
                                data-date="<?php echo strtotime($wallet->updated_at); ?>">
                                <td>#<?php echo $wallet->id; ?></td>
                                <td><?php echo htmlspecialchars($wallet->first_name . ' ' . $wallet->last_name); ?></td>
                                <td><?php echo htmlspecialchars($wallet->email); ?></td>
                                <td>
                                    <span class="fw-bold text-<?php echo $wallet->balance >= 0 ? 'success' : 'danger'; ?>">
                                        $<?php echo number_format($wallet->balance, 2); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($wallet->updated_at)); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo base_url('admin/credit_wallet/' . $wallet->user_id); ?>" class="btn btn-outline-success" title="Credit">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        <a href="<?php echo base_url('admin/debit_wallet/' . $wallet->user_id); ?>" class="btn btn-outline-warning" title="Debit">
                                            <i class="fas fa-minus"></i>
                                        </a>
                                        <a href="<?php echo base_url('admin/wallet_transactions/' . $wallet->user_id); ?>" class="btn btn-outline-info" title="Transactions">
                                            <i class="fas fa-history"></i>
                                        </a>
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
const balanceFilter = document.getElementById('balanceFilter');
const sortBy = document.getElementById('sortBy');
const table = document.getElementById('walletsTable');
const tbody = table.querySelector('tbody');
const rows = Array.from(tbody.querySelectorAll('tr'));

function filterWallets() {
    const searchTerm = searchInput.value.toLowerCase();
    const balanceFilterValue = balanceFilter.value;
    let filteredRows = rows.filter(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const email = row.getAttribute('data-email').toLowerCase();
        const balance = parseFloat(row.getAttribute('data-balance'));
        const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
        
        let matchesBalance = true;
        if (balanceFilterValue === 'positive') {
            matchesBalance = balance > 0;
        } else if (balanceFilterValue === 'negative') {
            matchesBalance = balance < 0;
        } else if (balanceFilterValue === 'zero') {
            matchesBalance = balance === 0;
        }
        
        return matchesSearch && matchesBalance;
    });

    // Sort rows
    const sortValue = sortBy.value;
    filteredRows.sort((a, b) => {
        if (sortValue === 'name') {
            return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
        } else if (sortValue === 'balance') {
            return parseFloat(b.getAttribute('data-balance')) - parseFloat(a.getAttribute('data-balance'));
        } else if (sortValue === 'date') {
            return b.getAttribute('data-date') - a.getAttribute('data-date');
        }
        return 0;
    });

    tbody.innerHTML = '';
    filteredRows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

// Event listeners
searchInput.addEventListener('input', filterWallets);
balanceFilter.addEventListener('change', filterWallets);
sortBy.addEventListener('change', filterWallets);
</script>

<?php $this->load->view('admin/includes/footer'); ?> 