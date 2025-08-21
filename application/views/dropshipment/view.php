<?php $this->load->view('admin/includes/header'); ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-eye me-2"></i>Order Details
                </h1>
                <p class="text-muted">View drop shipment order information</p>
            </div>
            <div>
                <a href="<?php echo base_url('dropshipment'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Orders
                </a>
                <?php if ($this->session->userdata('role') === 'admin'): ?>
                    <a href="<?php echo base_url('dropshipment/process/' . $order->id); ?>" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Process Order
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Order Number:</strong></td>
                                        <td><?php echo htmlspecialchars($order->order_number); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <?php if ($order->status === 'Pending'): ?>
                                                <span class="badge bg-warning fs-6"><?php echo $order->status; ?></span>
                                            <?php elseif ($order->status === 'Processed'): ?>
                                                <span class="badge bg-success fs-6"><?php echo $order->status; ?></span>
                                            <?php elseif ($order->status === 'Shipped'): ?>
                                                <span class="badge bg-info fs-6"><?php echo $order->status; ?></span>
                                            <?php elseif ($order->status === 'Delivered'): ?>
                                                <span class="badge bg-primary fs-6"><?php echo $order->status; ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger fs-6"><?php echo $order->status; ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Center:</strong></td>
                                        <td><?php echo htmlspecialchars($order->center); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($order->created_at)); ?></td>
                                    </tr>
                                    <?php if ($order->processed_at): ?>
                                    <tr>
                                        <td><strong>Processed:</strong></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($order->processed_at)); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Product:</strong></td>
                                        <td><?php echo htmlspecialchars($order->product_name); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Quantity:</strong></td>
                                        <td><?php echo $order->quantity; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Price:</strong></td>
                                        <td>
                                            <?php if ($order->price): ?>
                                                <strong class="text-success">₹<?php echo number_format($order->price, 2); ?></strong>
                                            <?php else: ?>
                                                <span class="text-muted">Not set</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Status:</strong></td>
                                        <td>
                                            <?php if (isset($order->payment_status)): ?>
                                                <?php if ($order->payment_status === 'Paid'): ?>
                                                    <span class="badge bg-success"><?php echo $order->payment_status; ?></span>
                                                <?php elseif ($order->payment_status === 'Partial'): ?>
                                                    <span class="badge bg-warning"><?php echo $order->payment_status; ?></span>
                                                <?php elseif ($order->payment_status === 'Overpaid'): ?>
                                                    <span class="badge bg-info"><?php echo $order->payment_status; ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><?php echo $order->payment_status; ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Unknown</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created By:</strong></td>
                                        <td><?php echo htmlspecialchars($order->creator_first_name . ' ' . $order->creator_last_name); ?></td>
                                    </tr>
                                    <?php if ($order->processed_by): ?>
                                    <tr>
                                        <td><strong>Processed By:</strong></td>
                                        <td><?php echo htmlspecialchars($order->processor_first_name . ' ' . $order->processor_last_name); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td><?php echo htmlspecialchars($order->customer_name); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <?php if ($order->customer_address): ?>
                                    <tr>
                                        <td><strong>Address:</strong></td>
                                        <td><?php echo nl2br(htmlspecialchars($order->customer_address)); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tracking Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Tracking Information</h6>
                        <button type="button" class="btn btn-primary btn-sm" onclick="showTrackingModal()">
                            <i class="fas fa-edit me-1"></i>Update Tracking
                        </button>
                    </div>
                    <div class="card-body">
                        <?php if ($order->tracking_number): ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Tracking Number:</strong><br>
                                    <?php echo htmlspecialchars($order->tracking_number); ?>
                                </div>
                                <div class="col-md-4">
                                    <strong>Carrier:</strong><br>
                                    <?php echo htmlspecialchars($order->tracking_carrier); ?>
                                </div>
                                <div class="col-md-4">
                                    <strong>Actions:</strong><br>
                                    <?php if ($order->tracking_url): ?>
                                        <a href="<?php echo htmlspecialchars($order->tracking_url); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-external-link-alt me-1"></i>Track Package
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-truck fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No tracking information available</p>
                                <button type="button" class="btn btn-primary" onclick="showTrackingModal()">
                                    <i class="fas fa-plus me-1"></i>Add Tracking Info
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Notes -->
                <?php if ($order->notes): ?>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($order->notes)); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if ($this->session->userdata('role') === 'admin' && $order->status === 'Pending'): ?>
                                <a href="<?php echo base_url('dropshipment/process/' . $order->id); ?>" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>Process Order
                                </a>
                            <?php endif; ?>
                            
                            <button type="button" class="btn btn-primary" onclick="showTrackingModal()">
                                <i class="fas fa-truck me-1"></i>Update Tracking
                            </button>
                            
                            <?php if ($this->session->userdata('role') === 'admin' && $order->status !== 'Delivered'): ?>
                                <button type="button" class="btn btn-warning" onclick="cancelOrder(<?php echo $order->id; ?>)">
                                    <i class="fas fa-times me-1"></i>Cancel Order
                                </button>
                            <?php endif; ?>
                            
                            <a href="<?php echo base_url('dropshipment'); ?>" class="btn btn-secondary">
                                <i class="fas fa-list me-1"></i>View All Orders
                            </a>
                            
                            <?php if ($this->session->userdata('role') === 'admin'): ?>
                                <button type="button" class="btn btn-danger" onclick="deleteOrder(<?php echo $order->id; ?>)">
                                    <i class="fas fa-trash me-1"></i>Delete Order
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tracking Modal -->
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Tracking Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="trackingForm">
                    <div class="form-group">
                        <label for="tracking_number">Tracking Number</label>
                        <input type="text" id="tracking_number" class="form-control" value="<?php echo htmlspecialchars($order->tracking_number ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="tracking_carrier">Carrier</label>
                        <select id="tracking_carrier" class="form-control">
                            <option value="">Select Carrier</option>
                            <?php foreach ($carriers as $key => $carrier): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($order->tracking_carrier === $key) ? 'selected' : ''; ?>>
                                    <?php echo $carrier; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tracking_url">Tracking URL (Optional)</label>
                        <input type="url" id="tracking_url" class="form-control" value="<?php echo htmlspecialchars($order->tracking_url ?? ''); ?>" placeholder="https://...">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateTracking(<?php echo $order->id; ?>)">Update Tracking</button>
            </div>
        </div>
    </div>
</div>

<script>
function showTrackingModal() {
    var modal = new bootstrap.Modal(document.getElementById('trackingModal'));
    modal.show();
}

function updateTracking(orderId) {
    const trackingNumber = document.getElementById('tracking_number').value;
    const trackingCarrier = document.getElementById('tracking_carrier').value;
    const trackingUrl = document.getElementById('tracking_url').value;
    
    fetch('<?php echo base_url('dropshipment/update_tracking'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            order_id: orderId,
            tracking_number: trackingNumber,
            tracking_carrier: trackingCarrier,
            tracking_url: trackingUrl
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tracking information updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating tracking information.');
    });
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('dropshipment/delete/'); ?>' + orderId;
    }
}

function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
        // Show loading state
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(btn => btn.disabled = true);
        
        fetch('<?php echo base_url('dropshipment/update_status'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                order_id: orderId,
                status: 'Cancelled'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order cancelled successfully!');
                window.location.href = '<?php echo base_url('dropshipment'); ?>';
            } else {
                alert('Error: ' + (data.message || 'Failed to cancel order'));
                buttons.forEach(btn => btn.disabled = false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while cancelling the order.');
            buttons.forEach(btn => btn.disabled = false);
        });
    }
}
</script>

<?php $this->load->view('admin/includes/footer'); ?> 