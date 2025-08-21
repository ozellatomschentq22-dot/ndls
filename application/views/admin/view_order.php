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
                <i class="fas fa-shopping-cart me-2"></i>View Order
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Orders
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

        <!-- Order Details -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-shopping-cart me-2 text-primary"></i>Order Details
                            </h5>
                            <span class="badge bg-<?php echo $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'danger'))); ?> fs-6">
                                <?php echo ucfirst($order->status); ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Order Number</label>
                                    <p class="mb-0">#<?php echo $order->order_number; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Total Amount</label>
                                    <p class="mb-0 text-primary fs-5">$<?php echo number_format($order->total_amount, 2); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Variant</label>
                                    <p class="mb-0"><?php echo $product->quantity; ?> pills</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Order Date</label>
                                    <p class="mb-0"><?php echo date('M j, Y H:i', strtotime($order->created_at)); ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if ($order->shipping_address): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Shipping Address</label>
                                <div class="border rounded p-3 bg-light">
                                    <?php echo nl2br(htmlspecialchars($order->shipping_address)); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($order->notes): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notes</label>
                                <div class="border rounded p-3 bg-light">
                                    <?php echo nl2br(htmlspecialchars($order->notes)); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Last Updated</label>
                                    <p class="mb-0"><?php echo date('M j, Y H:i', strtotime($order->updated_at)); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Order ID</label>
                                    <p class="mb-0">#<?php echo $order->id; ?></p>
                                </div>
                            </div>
                        </div>

                        <?php if ($order->status !== 'delivered' && $order->status !== 'cancelled'): ?>
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3">Update Status</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php if ($order->status === 'pending'): ?>
                                    <a href="<?php echo base_url('admin/update_order_status/' . $order->id . '/processing'); ?>" 
                                       class="btn btn-info btn-sm" 
                                       onclick="return confirm('Mark order as processing?')">
                                        <i class="fas fa-cog me-1"></i>Mark Processing
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($order->status === 'processing'): ?>
                                    <a href="<?php echo base_url('admin/update_order_status/' . $order->id . '/shipped'); ?>" 
                                       class="btn btn-primary btn-sm" 
                                       onclick="return confirm('Mark order as shipped?')">
                                        <i class="fas fa-shipping-fast me-1"></i>Mark Shipped
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($order->status === 'shipped'): ?>
                                    <a href="<?php echo base_url('admin/update_order_status/' . $order->id . '/delivered'); ?>" 
                                       class="btn btn-success btn-sm" 
                                       onclick="return confirm('Mark order as delivered?')">
                                        <i class="fas fa-check me-1"></i>Mark Delivered
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($order->status !== 'delivered'): ?>
                                    <a href="<?php echo base_url('admin/update_order_status/' . $order->id . '/cancelled'); ?>" 
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

                <!-- Tracking Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-truck me-2 text-info"></i>Tracking Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($order->tracking_number): ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tracking Number</label>
                                    <p class="mb-0"><?php echo htmlspecialchars($order->tracking_number); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Carrier</label>
                                    <p class="mb-0"><?php echo htmlspecialchars($order->carrier); ?></p>
                                </div>
                            </div>
                            
                            <?php if ($order->tracking_url): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tracking URL</label>
                                    <p class="mb-0">
                                        <a href="<?php echo htmlspecialchars($order->tracking_url); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-external-link-alt me-1"></i>Track Package
                                        </a>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <?php if ($order->shipped_at): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Shipped Date</label>
                                    <p class="mb-0"><?php echo date('M j, Y H:i', strtotime($order->shipped_at)); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($order->delivered_at): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Delivered Date</label>
                                    <p class="mb-0"><?php echo date('M j, Y H:i', strtotime($order->delivered_at)); ?></p>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-truck fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No tracking information available</p>
                            </div>
                        <?php endif; ?>

                        <!-- Add/Update Tracking Form -->
                        <?php if ($order->status !== 'cancelled'): ?>
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="mb-3"><?php echo $order->tracking_number ? 'Update' : 'Add'; ?> Tracking Information</h6>
                                <?php echo form_open('admin/update_tracking/' . $order->id); ?>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="tracking_number" class="form-label">Tracking Number</label>
                                            <input type="text" name="tracking_number" id="tracking_number" class="form-control" 
                                                   value="<?php echo set_value('tracking_number', $order->tracking_number); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="carrier" class="form-label">Carrier</label>
                                            <select name="carrier" id="carrier" class="form-select" required>
                                                <option value="">Select carrier...</option>
                                                <option value="USPS" <?php echo ($order->carrier === 'USPS') ? 'selected' : ''; ?>>USPS</option>
                                                <option value="FedEx" <?php echo ($order->carrier === 'FedEx') ? 'selected' : ''; ?>>FedEx</option>
                                                <option value="UPS" <?php echo ($order->carrier === 'UPS') ? 'selected' : ''; ?>>UPS</option>
                                                <option value="DHL" <?php echo ($order->carrier === 'DHL') ? 'selected' : ''; ?>>DHL</option>
                                                <option value="Other" <?php echo ($order->carrier === 'Other') ? 'selected' : ''; ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tracking_url" class="form-label">Tracking URL (Optional)</label>
                                        <input type="url" name="tracking_url" id="tracking_url" class="form-control" 
                                               value="<?php echo set_value('tracking_url', $order->tracking_url); ?>" 
                                               placeholder="https://tracking.example.com/track/123456789">
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i><?php echo $order->tracking_number ? 'Update' : 'Add'; ?> Tracking
                                        </button>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tracking Updates -->
                <?php if (isset($tracking_updates) && !empty($tracking_updates)): ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2 text-secondary"></i>Tracking Updates
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php foreach ($tracking_updates as $update): ?>
                                <div class="timeline-item mb-3">
                                    <div class="d-flex">
                                        <div class="timeline-marker bg-primary rounded-circle me-3" style="width: 12px; height: 12px; margin-top: 5px;"></div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($update->status); ?></h6>
                                                <small class="text-muted"><?php echo date('M j, Y H:i', strtotime($update->tracking_date)); ?></small>
                                            </div>
                                            <?php if ($update->location): ?>
                                                <p class="mb-1 text-muted"><i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($update->location); ?></p>
                                            <?php endif; ?>
                                            <?php if ($update->description): ?>
                                                <p class="mb-0 small"><?php echo htmlspecialchars($update->description); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer Name</label>
                            <p class="mb-0"><?php echo $customer->first_name . ' ' . $customer->last_name; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="mb-0"><?php echo $customer->email; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="mb-0"><?php echo $customer->phone ?: 'Not provided'; ?></p>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo base_url('admin/view_customer/' . $customer->id); ?>" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>View Customer Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-box me-2"></i>Product Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Name</label>
                            <p class="mb-0"><?php echo $product->name; ?></p>
                        </div>
                        <?php if ($product->brand): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Brand</label>
                            <p class="mb-0"><?php echo htmlspecialchars($product->brand); ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if ($product->strength): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Strength</label>
                            <p class="mb-0"><?php echo htmlspecialchars($product->strength); ?></p>
                        </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Price</label>
                            <p class="mb-0 text-success fs-5">$<?php echo number_format($product->price, 2); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 