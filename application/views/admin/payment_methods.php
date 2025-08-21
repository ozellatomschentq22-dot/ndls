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
                <i class="fas fa-credit-card me-2"></i>Payment Methods
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/add_payment_method'); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Payment Method
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
                                    Total Methods
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count($payment_methods); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-credit-card fa-2x text-muted"></i>
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
                                    Active Methods
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($payment_methods, function($m) { return $m->is_active; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-muted"></i>
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
                                    Inactive Methods
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($payment_methods, function($m) { return !$m->is_active; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-muted"></i>
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
                                    Custom Order
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($payment_methods, function($m) { return $m->sort_order > 0; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-sort fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payment Methods List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="paymentMethodsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Icon</th>
                                <th>Display Name</th>
                                <th>Payment Mode</th>
                                <th>Title</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payment_methods as $method): ?>
                            <tr>
                                <td>#<?php echo $method->id; ?></td>
                                <td>
                                    <i class="<?php echo $method->icon; ?> fa-lg"></i>
                                </td>
                                <td><?php echo htmlspecialchars($method->display_name); ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo isset($method->method_key) ? ucwords(str_replace('_', ' ', $method->method_key)) : 'N/A'; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($method->title); ?></td>
                                <td><?php echo $method->sort_order; ?></td>
                                <td>
                                    <?php if ($method->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo base_url('admin/edit_payment_method/' . $method->id); ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-<?php echo $method->is_active ? 'danger' : 'success'; ?>" 
                                                onclick="togglePaymentMethod(<?php echo $method->id; ?>)" 
                                                title="<?php echo $method->is_active ? 'Deactivate' : 'Activate'; ?>">
                                            <i class="fas fa-<?php echo $method->is_active ? 'ban' : 'check'; ?>"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="deletePaymentMethod(<?php echo $method->id; ?>)" title="Delete">
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
function togglePaymentMethod(methodId) {
    if (confirm('Are you sure you want to change this payment method\'s status?')) {
        window.location.href = '<?php echo base_url('admin/toggle_payment_method/'); ?>' + methodId;
    }
}

function deletePaymentMethod(methodId) {
    if (confirm('Are you sure you want to delete this payment method? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('admin/delete_payment_method/'); ?>' + methodId;
    }
}
</script>

<?php $this->load->view('admin/includes/footer'); ?> 