<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-users me-2 text-primary"></i>Customers
                </h1>
                <p class="text-muted mb-0">Manage all customer accounts and information.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('staff/add_customer'); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Customer
                </a>
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
                    <button type="button" class="btn btn-outline-primary" id="allCustomers">All</button>
                    <button type="button" class="btn btn-outline-success" id="activeCustomers">Active</button>
                    <button type="button" class="btn btn-outline-warning" id="inactiveCustomers">Inactive</button>
                </div>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($customers)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    <?php echo strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)); ?>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($customer->email); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($customer->email); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if (isset($customer->phone) && !empty($customer->phone)): ?>
                                            <a href="tel:<?php echo htmlspecialchars($customer->phone); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($customer->phone); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($customer->username); ?></td>
                                    <td>
                                        <?php if ($customer->status == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($customer->created_at)); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('staff/view_customer/' . $customer->id); ?>" class="btn btn-sm btn-outline-primary" title="View Details">
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
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No customers found</h5>
                        <p class="text-muted">There are no customers in the system yet.</p>
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
            window.location.href = '<?php echo base_url('staff/customers'); ?>?search=' + encodeURIComponent(searchTerm);
        }
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
    
    // Filter buttons
    document.getElementById('allCustomers').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/customers'); ?>';
    });
    
    document.getElementById('activeCustomers').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/customers'); ?>?status=active';
    });
    
    document.getElementById('inactiveCustomers').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/customers'); ?>?status=inactive';
    });
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 