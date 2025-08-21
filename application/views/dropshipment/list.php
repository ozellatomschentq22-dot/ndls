<?php $this->load->view('admin/includes/header'); ?>

<!-- Cache busting meta tags -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<div class="main-content">
    <div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shipping-fast me-2"></i>Drop Shipment Orders
            </h1>
            <p class="text-muted">Manage drop shipment orders and track their status</p>
        </div>
        <div>
            <a href="<?php echo base_url('dropshipment/add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add New Order
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $order_summary->total_orders ?? 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $order_summary->pending_count ?? 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Processed Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $order_summary->processed_count ?? 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Amount</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?php echo number_format($order_summary->total_amount ?? 0, 2); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo base_url('dropshipment'); ?>">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All Statuses</option>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?php echo $status; ?>" <?php echo ($filters['status'] === $status) ? 'selected' : ''; ?>>
                                        <?php echo $status; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="center">Center</label>
                            <select name="center" id="center" class="form-control">
                                <option value="">All Centers</option>
                                <?php foreach ($centers as $center): ?>
                                    <option value="<?php echo $center->name; ?>" <?php echo ($filters['center'] === $center->name) ? 'selected' : ''; ?>>
                                        <?php echo $center->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_from">Date From</label>
                            <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo $filters['date_from']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_to">Date To</label>
                            <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo $filters['date_to']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Order #, Customer, Product" value="<?php echo $filters['search']; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                                <a href="<?php echo base_url('dropshipment'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Center Summary -->
    <?php if (!empty($center_summary)): ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Center Summary</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Center</th>
                            <th>Total Orders</th>
                            <th>Pending</th>
                            <th>Processed</th>
                            <th>Shipped</th>
                            <th>Delivered</th>
                            <th>Cancelled</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($center_summary as $summary): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($summary->center); ?></strong></td>
                            <td><?php echo $summary->total_orders; ?></td>
                            <td><span class="badge bg-warning"><?php echo $summary->pending_count; ?></span></td>
                            <td><span class="badge bg-success"><?php echo $summary->processed_count; ?></span></td>
                            <td><span class="badge bg-info"><?php echo $summary->shipped_count; ?></span></td>
                            <td><span class="badge bg-primary"><?php echo $summary->delivered_count; ?></span></td>
                            <td><span class="badge bg-danger"><?php echo $summary->cancelled_count; ?></span></td>
                            <td><strong>₹<?php echo number_format($summary->total_amount, 2); ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Orders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Drop Shipment Orders</h6>
        </div>
        <div class="card-body">
            <?php if (empty($orders)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No orders found</h5>
                    <p class="text-muted">No drop shipment orders match your current filters.</p>
                    <a href="<?php echo base_url('dropshipment/add'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add First Order
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Created</th>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Tracking</th>
                                <?php if ($this->session->userdata('role') === 'admin'): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <div><?php echo date('M j, Y', strtotime($order->created_at)); ?></div>
                                    <small class="text-muted"><?php echo date('g:i A', strtotime($order->created_at)); ?></small>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('dropshipment/view/' . $order->id); ?>" class="text-decoration-none">
                                        <strong class="text-primary"><?php echo htmlspecialchars($order->order_number); ?></strong>
                                    </a>
                                </td>
                                <td>
                                    <div><?php echo htmlspecialchars($order->customer_name); ?></div>
                                </td>
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
                                    <?php if ($order->tracking_number): ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($order->tracking_number); ?></strong>
                                            <?php if ($order->tracking_url): ?>
                                                <a href="<?php echo htmlspecialchars($order->tracking_url); ?>" target="_blank" class="btn btn-outline-primary btn-sm ms-1" title="Track Package">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($order->tracking_carrier): ?>
                                            <small class="text-muted"><?php echo htmlspecialchars($order->tracking_carrier); ?></small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not available</span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($this->session->userdata('role') === 'admin'): ?>
                                <td>
                                    <div class="btn-group" role="group">
                                        
                                        <?php if ($this->session->userdata('role') === 'admin' && $order->status === 'Pending'): ?>
                                            <a href="<?php echo base_url('dropshipment/process/' . $order->id); ?>" class="btn btn-outline-success btn-sm" title="Process">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (in_array($order->status, ['Processed', 'Shipped']) && $this->session->userdata('role') === 'admin'): ?>
                                            <button type="button" class="btn btn-outline-warning btn-sm" title="Update Tracking" 
                                                    onclick="showTrackingModal(<?php echo $order->id; ?>, '<?php echo htmlspecialchars($order->tracking_number ?? ''); ?>', '<?php echo htmlspecialchars($order->tracking_carrier ?? ''); ?>', '<?php echo htmlspecialchars($order->tracking_url ?? ''); ?>')">
                                                <i class="fas fa-truck"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($this->session->userdata('role') === 'admin'): ?>
                                            <button type="button" class="btn btn-outline-danger btn-sm" title="Delete" 
                                                    onclick="deleteOrder(<?php echo $order->id; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($pagination): ?>
                    <div class="mt-4">
                        <?php echo $pagination; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Force page refresh to prevent caching issues
if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
    window.location.reload();
}

// Prevent browser caching
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        window.location.reload();
    }
});

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('dropshipment/delete/'); ?>' + orderId;
    }
}

function showTrackingModal(orderId, trackingNumber, trackingCarrier, trackingUrl) {
    // Set modal values
    document.getElementById('tracking_order_id').value = orderId;
    document.getElementById('tracking_number').value = trackingNumber;
    document.getElementById('tracking_carrier').value = trackingCarrier;
    document.getElementById('tracking_url').value = trackingUrl;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('trackingModal'));
    modal.show();
}

function updateTracking() {
    const orderId = document.getElementById('tracking_order_id').value;
    const trackingNumber = document.getElementById('tracking_number').value;
    const trackingCarrier = document.getElementById('tracking_carrier').value;
    const trackingUrl = document.getElementById('tracking_url').value;
    
    // Show loading state
    document.getElementById('updateTrackingBtn').disabled = true;
    document.getElementById('updateTrackingBtn').innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
    
    // Send AJAX request
    fetch('<?php echo base_url('dropshipment/update_tracking'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'order_id=' + orderId + '&tracking_number=' + encodeURIComponent(trackingNumber) + '&tracking_carrier=' + encodeURIComponent(trackingCarrier) + '&tracking_url=' + encodeURIComponent(trackingUrl)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal and reload page
            const modal = bootstrap.Modal.getInstance(document.getElementById('trackingModal'));
            modal.hide();
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Failed to update tracking information.'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating tracking information. Please try again.');
    })
    .finally(() => {
        // Reset button state
        document.getElementById('updateTrackingBtn').disabled = false;
        document.getElementById('updateTrackingBtn').innerHTML = '<i class="fas fa-save me-1"></i>Update Tracking';
    });
}
</script>

<!-- Tracking Modal -->
<div class="modal fade" id="trackingModal" tabindex="-1" aria-labelledby="trackingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trackingModalLabel">
                    <i class="fas fa-truck me-2"></i>Update Tracking Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="tracking_order_id">
                
                <div class="form-group mb-3">
                    <label for="tracking_number">Tracking Number</label>
                    <input type="text" class="form-control" id="tracking_number" placeholder="Enter tracking number">
                </div>
                
                <div class="form-group mb-3">
                    <label for="tracking_carrier">Carrier</label>
                    <select class="form-control" id="tracking_carrier">
                        <option value="">Select Carrier</option>
                        <option value="FedEx">FedEx</option>
                        <option value="UPS">UPS</option>
                        <option value="USPS">USPS</option>
                        <option value="DHL">DHL</option>
                        <option value="Amazon">Amazon</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="tracking_url">Tracking URL (Optional)</label>
                    <input type="url" class="form-control" id="tracking_url" placeholder="https://example.com/track/123456">
                    <small class="form-text text-muted">Direct link to track the package</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateTrackingBtn" onclick="updateTracking()">
                    <i class="fas fa-save me-1"></i>Update Tracking
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 