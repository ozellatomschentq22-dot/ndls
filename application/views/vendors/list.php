<div class="d-flex">
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-handshake me-2"></i>Manage Vendors
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('vendors/add'); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Vendor
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

        <!-- Vendor Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-primary fw-bold small mb-1">Total Vendors</div>
                                <div class="h4 mb-0 fw-bold"><?php echo count($vendors); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-handshake fa-2x text-muted"></i>
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
                                <div class="text-uppercase text-success fw-bold small mb-1">Active Vendors</div>
                                <div class="h4 mb-0 fw-bold">
                                    <?php 
                                    $active_count = 0;
                                    foreach ($vendors as $vendor) {
                                        if ($vendor->status === 'active') $active_count++;
                                    }
                                    echo $active_count;
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-muted"></i>
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
                                <div class="text-uppercase text-info fw-bold small mb-1">Flat Rate Vendors</div>
                                <div class="h4 mb-0 fw-bold">
                                    <?php 
                                    $flat_count = 0;
                                    foreach ($vendors as $vendor) {
                                        if ($vendor->payout_type === 'flat') $flat_count++;
                                    }
                                    echo $flat_count;
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-muted"></i>
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
                                <div class="text-uppercase text-warning fw-bold small mb-1">Percentage Vendors</div>
                                <div class="h4 mb-0 fw-bold">
                                    <?php 
                                    $percentage_count = 0;
                                    foreach ($vendors as $vendor) {
                                        if ($vendor->payout_type === 'percentage') $percentage_count++;
                                    }
                                    echo $percentage_count;
                                    ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-percentage fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo base_url('vendors'); ?>" class="row g-3">
                    <div class="col-md-3">
                        <label for="name" class="form-label">Vendor Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $filters['name']; ?>" placeholder="Search vendor name">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="payout_type" class="form-label">Payout Type</label>
                        <select class="form-select" id="payout_type" name="payout_type">
                            <option value="">All Types</option>
                            <?php foreach ($payout_types as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo ($filters['payout_type'] == $type) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?php echo $status; ?>" <?php echo ($filters['status'] == $status) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($status); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="<?php echo base_url('vendors'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vendors Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Vendors</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Payout Type</th>
                                <th>Rate Details</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($vendors)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No vendors found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($vendors as $vendor): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($vendor->name); ?></strong></td>
                                        <td>
                                            <?php if ($vendor->payout_type === 'flat'): ?>
                                                <span class="badge bg-info">Flat Rate</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Percentage</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($vendor->payout_type === 'flat'): ?>
                                                <span class="text-success">$1 = ₹<?php echo number_format($vendor->flat_rate_inr, 2); ?></span>
                                            <?php else: ?>
                                                <span class="text-warning"><?php echo $vendor->percentage_rate; ?>% at ₹<?php echo number_format($vendor->percentage_inr_rate, 2); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($vendor->status === 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($vendor->creator_first_name . ' ' . $vendor->creator_last_name); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($vendor->created_at)); ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo base_url('vendors/profile/' . $vendor->id); ?>" class="btn btn-outline-primary" title="View Profile">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                                <a href="<?php echo base_url('vendors/view/' . $vendor->id); ?>" class="btn btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo base_url('vendors/edit/' . $vendor->id); ?>" class="btn btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger" title="Delete" 
                                                        onclick="deleteVendor(<?php echo $vendor->id; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (!empty($pagination)): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo $pagination; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteVendor(vendorId) {
    if (confirm('Are you sure you want to delete this vendor? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('vendors/delete/'); ?>' + vendorId;
    }
}
</script> 