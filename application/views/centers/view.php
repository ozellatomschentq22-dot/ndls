<?php $this->load->view('admin/includes/header'); ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-building me-2"></i>Center Details
                </h1>
                <p class="text-muted">View detailed information about <?php echo htmlspecialchars($center->name); ?></p>
            </div>
            <div>
                <a href="<?php echo base_url('centers'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Centers
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Center Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Center Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Center Name:</strong></td>
                                        <td><?php echo htmlspecialchars($center->name); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Location:</strong></td>
                                        <td>
                                            <?php if ($center->location): ?>
                                                <?php echo htmlspecialchars($center->location); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Not specified</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <?php if ($center->status === 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($center->created_at)); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Center ID:</strong></td>
                                        <td><code><?php echo $center->id; ?></code></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Center Orders -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Orders from this Center</h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($orders)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No orders found</h5>
                                <p class="text-muted">No orders have been assigned to this center yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Product</th>
                                            <th>Status</th>
                                            <th>Price</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url('dropshipment/view/' . $order->id); ?>" class="text-decoration-none">
                                                    <strong class="text-primary"><?php echo htmlspecialchars($order->order_number); ?></strong>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($order->customer_name); ?></td>
                                            <td>
                                                <div><?php echo htmlspecialchars($order->product_name); ?></div>
                                                <small class="text-muted">Qty: <?php echo $order->quantity; ?></small>
                                            </td>
                                            <td>
                                                <?php if ($order->status === 'Pending'): ?>
                                                    <span class="badge bg-warning"><?php echo $order->status; ?></span>
                                                <?php elseif ($order->status === 'Processed'): ?>
                                                    <span class="badge bg-success"><?php echo $order->status; ?></span>
                                                <?php elseif ($order->status === 'Shipped'): ?>
                                                    <span class="badge bg-info"><?php echo $order->status; ?></span>
                                                <?php elseif ($order->status === 'Delivered'): ?>
                                                    <span class="badge bg-primary"><?php echo $order->status; ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><?php echo $order->status; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($order->price): ?>
                                                    <strong>₹<?php echo number_format($order->price, 2); ?></strong>
                                                <?php else: ?>
                                                    <span class="text-muted">Not set</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div><?php echo date('M j, Y', strtotime($order->created_at)); ?></div>
                                                <small class="text-muted"><?php echo date('g:i A', strtotime($order->created_at)); ?></small>
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

            <div class="col-lg-4">
                <!-- Center Statistics -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Center Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-primary mb-1"><?php echo $stats->total_orders ?? 0; ?></h4>
                                    <small class="text-muted">Total Orders</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-success mb-1">₹<?php echo number_format($stats->total_amount ?? 0, 2); ?></h4>
                                    <small class="text-muted">Total Amount</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-warning mb-1"><?php echo $stats->pending_count ?? 0; ?></h4>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-info mb-1"><?php echo $stats->processed_count ?? 0; ?></h4>
                                    <small class="text-muted">Processed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo base_url('centers/edit/' . $center->id); ?>" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i>Edit Center
                            </a>
                            
                            <button type="button" class="btn btn-<?php echo $center->status === 'active' ? 'warning' : 'success'; ?>" 
                                    onclick="toggleCenterStatus(<?php echo $center->id; ?>)">
                                <i class="fas fa-<?php echo $center->status === 'active' ? 'pause' : 'play'; ?> me-1"></i>
                                <?php echo $center->status === 'active' ? 'Deactivate' : 'Activate'; ?> Center
                            </button>
                            
                            <a href="<?php echo base_url('dropshipment/add'); ?>" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i>Add Order to this Center
                            </a>
                            
                            <button type="button" class="btn btn-danger" 
                                    onclick="deleteCenter(<?php echo $center->id; ?>, '<?php echo htmlspecialchars($center->name); ?>')">
                                <i class="fas fa-trash me-1"></i>Delete Center
                            </button>
                        </div>
                    </div>
                </div>
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