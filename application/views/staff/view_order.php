<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-shopping-bag me-2 text-success"></i>Order Details
                </h1>
                <p class="text-muted mb-0">View and manage order information.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/orders'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="row">
            <!-- Order Details -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Order Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Order Number:</label>
                                    <div class="h5 text-primary"><?php echo htmlspecialchars($order->order_number); ?></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Order Date:</label>
                                    <div><?php echo date('F j, Y \a\t g:i A', strtotime($order->created_at)); ?></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status:</label>
                                    <div>
                                        <?php
                                        $status_colors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $color = isset($status_colors[$order->status]) ? $status_colors[$order->status] : 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $color; ?> fs-6">
                                            <?php echo ucfirst($order->status); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Total Amount:</label>
                                    <div class="h5 text-success">$<?php echo number_format($order->total_amount, 2); ?></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Customer:</label>
                                    <div>
                                        <a href="<?php echo base_url('staff/view_customer/' . $order->user_id); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($order->first_name . ' ' . $order->last_name); ?>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Customer Email:</label>
                                    <div>
                                        <a href="mailto:<?php echo htmlspecialchars($order->email); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($order->email); ?>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tracking Number:</label>
                                    <div>
                                        <?php if (!empty($order->tracking_number)): ?>
                                            <span class="badge bg-info fs-6"><?php echo htmlspecialchars($order->tracking_number); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Not provided</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Last Updated:</label>
                                    <div><?php echo date('F j, Y \a\t g:i A', strtotime($order->updated_at)); ?></div>
                                </div>
                            </div>
                        </div>

                        <?php if ($order->status !== 'delivered' && $order->status !== 'cancelled'): ?>
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3">Update Status</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php if ($order->status === 'pending'): ?>
                                    <a href="<?php echo base_url('staff/update_order_status/' . $order->id . '/processing'); ?>" 
                                       class="btn btn-info btn-sm" 
                                       onclick="return confirm('Mark order as processing?')">
                                        <i class="fas fa-cog me-1"></i>Mark Processing
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($order->status === 'processing'): ?>
                                    <a href="<?php echo base_url('staff/update_order_status/' . $order->id . '/shipped'); ?>" 
                                       class="btn btn-primary btn-sm" 
                                       onclick="return confirm('Mark order as shipped?')">
                                        <i class="fas fa-shipping-fast me-1"></i>Mark Shipped
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($order->status === 'shipped'): ?>
                                    <a href="<?php echo base_url('staff/update_order_status/' . $order->id . '/delivered'); ?>" 
                                       class="btn btn-success btn-sm" 
                                       onclick="return confirm('Mark order as delivered?')">
                                        <i class="fas fa-check me-1"></i>Mark Delivered
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($order->status !== 'delivered'): ?>
                                    <a href="<?php echo base_url('staff/update_order_status/' . $order->id . '/cancelled'); ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Cancel this order? This action cannot be undone.')">
                                        <i class="fas fa-times me-1"></i>Cancel Order
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-pills me-2"></i>Product Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Product Name:</label>
                                    <div class="h6"><?php echo htmlspecialchars($order->product_name); ?></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Brand:</label>
                                    <div><?php echo htmlspecialchars($order->brand); ?></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Strength:</label>
                                    <div><?php echo htmlspecialchars($order->strength); ?></div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Variant:</label>
                                    <div><?php echo htmlspecialchars($order->product_quantity); ?> pills</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Price:</label>
                                    <div class="h6 text-success">$<?php echo number_format($order->total_amount, 2); ?></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Product ID:</label>
                                    <div><?php echo $order->product_id; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>Order Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="fas fa-edit me-1"></i>Update Status
                            </button>
                            
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateTrackingModal">
                                <i class="fas fa-truck me-1"></i>Update Tracking
                            </button>
                            
                            <a href="<?php echo base_url('staff/view_customer/' . $order->user_id); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-user me-1"></i>View Customer
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Order Timeline
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Created</h6>
                                    <small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($order->created_at)); ?></small>
                                </div>
                            </div>
                            
                            <?php if ($order->status != 'pending'): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Processing</h6>
                                    <small class="text-muted">Order moved to processing</small>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (in_array($order->status, ['shipped', 'delivered'])): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Shipped</h6>
                                    <small class="text-muted">Order has been shipped</small>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($order->status == 'delivered'): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Delivered</h6>
                                    <small class="text-muted">Order has been delivered</small>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStatusForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="orderStatus" class="form-label">Order Status</label>
                        <select class="form-select" id="orderStatus" name="status" required>
                            <option value="pending" <?php echo $order->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="processing" <?php echo $order->status == 'processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="shipped" <?php echo $order->status == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="delivered" <?php echo $order->status == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo $order->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Tracking Modal -->
<div class="modal fade" id="updateTrackingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Tracking Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateTrackingForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="trackingNumber" class="form-label">Tracking Number</label>
                        <input type="text" class="form-control" id="trackingNumber" name="tracking_number" 
                               value="<?php echo htmlspecialchars($order->tracking_number); ?>" placeholder="Enter tracking number">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Tracking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -29px;
    top: 17px;
    width: 2px;
    height: calc(100% + 10px);
    background-color: #dee2e6;
}

.timeline-content h6 {
    margin: 0;
    font-size: 0.875rem;
}

.timeline-content small {
    font-size: 0.75rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update Status Form
    document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('order_id', '<?php echo $order->id; ?>');
        
        fetch('<?php echo base_url('staff/update_order_status'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating order status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating order status');
        });
    });
    
    // Update Tracking Form
    document.getElementById('updateTrackingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('order_id', '<?php echo $order->id; ?>');
        
        fetch('<?php echo base_url('staff/update_tracking'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating tracking: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating tracking');
        });
    });
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 