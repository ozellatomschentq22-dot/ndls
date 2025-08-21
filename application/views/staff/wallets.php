<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-wallet me-2 text-success"></i>Customer Wallets
                </h1>
                <p class="text-muted mb-0">View customer wallet balances and transactions.</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">$<?php echo number_format($wallet_summary->total_balance, 2); ?></h4>
                                <small>Total Customer Wallet Balance</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $wallet_summary->total_users; ?></h4>
                                <small>Total Customers</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $total_wallets; ?></h4>
                                <small>Active Customer Wallets</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-wallet fa-2x"></i>
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
                    <input type="text" class="form-control" id="searchInput" placeholder="Search customers...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="allWallets">All</button>
                    <button type="button" class="btn btn-outline-success" id="positiveBalance">Positive</button>
                    <button type="button" class="btn btn-outline-danger" id="negativeBalance">Negative</button>
                </div>
            </div>
        </div>

        <!-- Wallets Table -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($wallets)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($wallets as $wallet): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-success rounded-circle">
                                                    <?php echo strtoupper(substr($wallet->first_name, 0, 1) . substr($wallet->last_name, 0, 1)); ?>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($wallet->first_name . ' ' . $wallet->last_name); ?></div>
                                                <small class="text-muted">ID: <?php echo $wallet->user_id; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($wallet->username); ?></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($wallet->email); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($wallet->email); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="fw-bold <?php echo $wallet->balance >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            $<?php echo number_format($wallet->balance, 2); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($wallet->balance >= 0): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Low Balance</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($wallet->updated_at)); ?></td>
                                    <td>
                                        <a href="<?php echo base_url('staff/view_wallet/' . $wallet->id); ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
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
                        <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No wallets found</h5>
                        <p class="text-muted">There are no wallets in the system yet.</p>
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
            window.location.href = '<?php echo base_url('staff/wallets'); ?>?search=' + encodeURIComponent(searchTerm);
        }
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
    
    // Filter buttons
    document.getElementById('allWallets').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/wallets'); ?>';
    });
    
    document.getElementById('positiveBalance').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/wallets'); ?>?balance=positive';
    });
    
    document.getElementById('negativeBalance').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/wallets'); ?>?balance=negative';
    });
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 