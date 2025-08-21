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
        <i class="fas fa-tags me-2"></i>Customer Pricing Management
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
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Customers
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($customers); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            With Custom Pricing
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo count(array_filter($customers, function($c) { return $c->custom_prices_count > 0; })); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tag fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customers Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list me-2"></i>Customer Pricing Overview
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Customer Type</th>
                        <th>Discount %</th>
                        <th>Custom Prices</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3">
                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></div>
                                    <small class="text-muted">ID: #<?php echo $customer->id; ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($customer->email); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $customer->customer_type === 'premium' ? 'warning' : ($customer->customer_type === 'wholesale' ? 'success' : 'secondary'); ?>">
                                <?php echo ucfirst($customer->customer_type ?? 'regular'); ?>
                            </span>
                        </td>
                        <td>
                            <?php if (isset($customer->discount_percentage) && $customer->discount_percentage > 0): ?>
                                <span class="badge bg-success">
                                    <?php echo $customer->discount_percentage; ?>%
                                </span>
                            <?php else: ?>
                                <span class="text-muted">0%</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($customer->custom_prices_count > 0): ?>
                                <span class="badge bg-info">
                                    <?php echo $customer->custom_prices_count; ?> custom prices
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Default pricing</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo ($customer->status === 'active') ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($customer->status ?? 'active'); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo base_url('admin/customer_pricing/' . $customer->id); ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit me-1"></i>Manage Pricing
                                </a>
                                <a href="<?php echo base_url('admin/view_customer/' . $customer->id); ?>" 
                                   class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>View
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

<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "order": [[ 4, "desc" ]], // Sort by custom prices count
        "pageLength": 25
    });
});
</script>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 