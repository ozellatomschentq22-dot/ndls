<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-shopping-bag me-2"></i>Order Details
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?php echo base_url('customer/orders'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Orders
                        </a>
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

                <div class="row">
                    <!-- Order Information -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Order Information
                                    </h5>
                                    <span class="badge bg-<?php echo $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'danger'))); ?> fs-6">
                                        <?php echo ucfirst($order->status); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Order Number:</strong><br>
                                        <span class="text-muted"><?php echo $order->order_number; ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Order Date:</strong><br>
                                        <span class="text-muted"><?php echo date('M j, Y H:i', strtotime($order->created_at)); ?></span>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Product:</strong><br>
                                        <span class="text-muted"><?php echo htmlspecialchars($order->product_name); ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Variant:</strong><br>
                                        <span class="badge bg-primary"><?php echo $order->product_quantity; ?> pills</span>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Total Amount:</strong><br>
                                        <span class="fw-bold text-success">$<?php echo number_format($order->total_amount, 2); ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Payment Status:</strong><br>
                                        <span class="badge bg-success">Paid</span>
                                    </div>
                                </div>
                                
                                <?php if (!empty($order->notes)): ?>
                                    <div class="mb-3">
                                        <strong>Order Notes:</strong><br>
                                        <div class="mt-2 p-3 bg-light rounded">
                                            <?php echo nl2br(htmlspecialchars($order->notes)); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-shipping-fast me-2"></i>Shipping Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (isset($shipping_address) && $shipping_address): ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Recipient:</strong><br>
                                            <span class="text-muted"><?php echo htmlspecialchars($shipping_address->full_name); ?></span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Phone:</strong><br>
                                            <span class="text-muted"><?php echo htmlspecialchars($shipping_address->phone); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <strong>Address:</strong><br>
                                        <span class="text-muted">
                                            <?php echo htmlspecialchars($shipping_address->address_line1); ?><br>
                                            <?php if (!empty($shipping_address->address_line2)): ?>
                                                <?php echo htmlspecialchars($shipping_address->address_line2); ?><br>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($shipping_address->city_state_zip); ?><br>
                                            <?php echo htmlspecialchars($shipping_address->country); ?>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted">No shipping address found.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Tracking Information -->
                        <?php if ($order->tracking_number): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-truck me-2 text-info"></i>Tracking Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Tracking Number:</strong><br>
                                        <span class="fw-bold text-primary"><?php echo htmlspecialchars($order->tracking_number); ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Carrier:</strong><br>
                                        <span class="text-muted"><?php echo htmlspecialchars($order->carrier); ?></span>
                                    </div>
                                </div>
                                
                                <?php if ($order->tracking_url): ?>
                                    <div class="mb-3">
                                        <strong>Track Package:</strong><br>
                                        <a href="<?php echo htmlspecialchars($order->tracking_url); ?>" target="_blank" class="btn btn-primary">
                                            <i class="fas fa-external-link-alt me-2"></i>Track Package
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if ($order->shipped_at): ?>
                                    <div class="mb-3">
                                        <strong>Shipped Date:</strong><br>
                                        <span class="text-muted"><?php echo date('M j, Y H:i', strtotime($order->shipped_at)); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($order->delivered_at): ?>
                                    <div class="mb-3">
                                        <strong>Delivered Date:</strong><br>
                                        <span class="text-success fw-bold"><?php echo date('M j, Y H:i', strtotime($order->delivered_at)); ?></span>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($tracking_updates) && !empty($tracking_updates)): ?>
                                    <div class="mt-4 pt-3 border-top">
                                        <h6 class="mb-3">Tracking Updates</h6>
                                        <div class="timeline">
                                            <?php foreach ($tracking_updates as $update): ?>
                                                <div class="timeline-item mb-3">
                                                    <div class="timeline-marker bg-primary"></div>
                                                    <div class="timeline-content">
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
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Order Timeline -->
                        <div class="card">
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
                                            <h6>Order Placed</h6>
                                            <p class="text-muted mb-0"><?php echo date('M j, Y H:i', strtotime($order->created_at)); ?></p>
                                        </div>
                                    </div>
                                    
                                    <?php if ($order->status !== 'pending'): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-info"></div>
                                            <div class="timeline-content">
                                                <h6>Order Confirmed</h6>
                                                <p class="text-muted mb-0">Order has been confirmed and is being processed</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (in_array($order->status, ['shipped', 'delivered'])): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <h6>Order Shipped</h6>
                                                <p class="text-muted mb-0">Your order has been shipped</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($order->status === 'delivered'): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h6>Order Delivered</h6>
                                                <p class="text-muted mb-0">Your order has been delivered</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($order->status === 'cancelled'): ?>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-danger"></div>
                                            <div class="timeline-content">
                                                <h6>Order Cancelled</h6>
                                                <p class="text-muted mb-0">Order has been cancelled</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Order Actions -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-cog me-2"></i>Order Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="<?php echo base_url('customer/support_tickets'); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-ticket-alt me-2"></i>Get Support
                                    </a>
                                    
                                    <a href="<?php echo base_url('customer/place_order?product_id=' . $order->product_id); ?>" class="btn btn-outline-success">
                                        <i class="fas fa-plus me-2"></i>Order Again
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt me-2"></i>Order Summary
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Product:</span>
                                    <span><?php echo htmlspecialchars($order->product_name); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Variant:</span>
                                    <span><?php echo $order->product_quantity; ?> pills</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Price:</span>
                                    <span>$<?php echo number_format($order->total_amount, 2); ?></span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span class="text-success">$<?php echo number_format($order->total_amount, 2); ?></span>
                                </div>
                            </div>
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
                </style>

<?php $this->load->view('customer/common/footer'); ?> 