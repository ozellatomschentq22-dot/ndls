<?php $this->load->view('admin/includes/header'); ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-check me-2"></i>Process Order
                </h1>
                <p class="text-muted">Set price and process drop shipment order</p>
            </div>
            <div>
                <a href="<?php echo base_url('dropshipment'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Orders
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Process Order</h6>
                    </div>
                    <div class="card-body">
                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Please correct the following errors:
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Order Summary -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-1"></i>Order Summary</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Order #:</strong> <?php echo htmlspecialchars($order->order_number); ?><br>
                                    <strong>Customer:</strong> <?php echo htmlspecialchars($order->customer_name); ?><br>
                                    <strong>Product:</strong> <?php echo htmlspecialchars($order->product_name); ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Quantity:</strong> <?php echo $order->quantity; ?><br>
                                    <strong>Center:</strong> <?php echo htmlspecialchars($order->center); ?><br>
                                    <strong>Created:</strong> <?php echo date('M j, Y', strtotime($order->created_at)); ?>
                                </div>
                            </div>
                        </div>

                        <?php echo form_open('dropshipment/process/' . $order->id); ?>
                            <div class="form-group">
                                <label for="price">Order Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="price" id="price" class="form-control" 
                                           value="<?php echo set_value('price'); ?>" step="0.01" required
                                           placeholder="Enter the order price (can be negative)">
                                </div>
                                <small class="form-text text-muted">Enter the total price for this order in INR (₹). Negative values are allowed.</small>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>Process Order
                                </button>
                                <a href="<?php echo base_url('dropshipment'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Processing Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-1"></i>Important</h6>
                            <ul class="mb-0">
                                <li>This action will change the order status to "Processed"</li>
                                <li>The price will be set and cannot be changed later</li>
                                <li>Only admin users can process orders</li>
                                <li>Processing timestamp will be recorded</li>
                            </ul>
                        </div>

                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-1"></i>Next Steps</h6>
                            <p class="mb-0">After processing, you can:</p>
                            <ul class="mb-0">
                                <li>Update tracking information</li>
                                <li>Change order status to "Shipped" or "Delivered"</li>
                                <li>Add notes or additional information</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 