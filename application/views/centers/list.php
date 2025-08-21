<?php $this->load->view('admin/includes/header'); ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-building me-2"></i>Manage Centers
                </h1>
                <p class="text-muted">Manage drop shipment centers and warehouses</p>
            </div>
            <div>
                <a href="<?php echo base_url('centers/add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New Center
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Centers</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($centers); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
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
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Centers</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo count(array_filter($centers, function($c) { return $c->status === 'active'; })); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive Centers</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo count(array_filter($centers, function($c) { return $c->status === 'inactive'; })); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-pause-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Orders</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php echo array_sum(array_column($center_stats, 'total_orders')); ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Centers Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Centers</h6>
            </div>
            <div class="card-body">
                <?php if (empty($centers)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No centers found</h5>
                        <p class="text-muted">No centers have been created yet.</p>
                        <a href="<?php echo base_url('centers/add'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add First Center
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Center Name</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Orders</th>
                                    <th>Total Amount</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($centers as $center): ?>
                                <?php 
                                    // Find center stats
                                    $center_stat = null;
                                    foreach ($center_stats as $stat) {
                                        if ($stat->center === $center->name) {
                                            $center_stat = $stat;
                                            break;
                                        }
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo base_url('centers/view/' . $center->id); ?>" class="text-decoration-none">
                                            <strong class="text-primary"><?php echo htmlspecialchars($center->name); ?></strong>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if ($center->location): ?>
                                            <?php echo htmlspecialchars($center->location); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not specified</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($center->status === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($center_stat): ?>
                                            <strong><?php echo $center_stat->total_orders; ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                Pending: <?php echo $center_stat->pending_count; ?> | 
                                                Processed: <?php echo $center_stat->processed_count; ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">0</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($center_stat && $center_stat->total_amount > 0): ?>
                                            <strong class="text-success">₹<?php echo number_format($center_stat->total_amount, 2); ?></strong>
                                        <?php else: ?>
                                            <span class="text-muted">₹0.00</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?php echo date('M j, Y', strtotime($center->created_at)); ?></div>
                                        <small class="text-muted"><?php echo date('g:i A', strtotime($center->created_at)); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('centers/edit/' . $center->id); ?>" class="btn btn-outline-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <button type="button" class="btn btn-outline-<?php echo $center->status === 'active' ? 'warning' : 'success'; ?> btn-sm" 
                                                    title="<?php echo $center->status === 'active' ? 'Deactivate' : 'Activate'; ?>"
                                                    onclick="toggleCenterStatus(<?php echo $center->id; ?>)">
                                                <i class="fas fa-<?php echo $center->status === 'active' ? 'pause' : 'play'; ?>"></i>
                                            </button>
                                            
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Delete" 
                                                    onclick="deleteCenter(<?php echo $center->id; ?>, '<?php echo htmlspecialchars($center->name); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCenterStatus(centerId) {
    fetch('<?php echo base_url('centers/toggle_status/'); ?>' + centerId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating center status.');
    });
}

function deleteCenter(centerId, centerName) {
    if (confirm('Are you sure you want to delete the center "' + centerName + '"? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('centers/delete/'); ?>' + centerId;
    }
}
</script>

<?php $this->load->view('admin/includes/footer'); ?> 