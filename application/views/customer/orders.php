<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-shopping-bag me-2"></i>My Orders
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('customer/products'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Place New Order
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

                <!-- Orders List -->
                <?php if (!empty($orders)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Order History
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Product</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Tracking</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo $order->order_number; ?></strong>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?php echo htmlspecialchars($order->product_name); ?></div>
                                                <small class="text-muted">Variant: <?php echo $order->product_quantity; ?> pills</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold">$<?php echo number_format($order->total_amount, 2); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'danger'))); ?>">
                                                    <?php echo ucfirst($order->status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($order->tracking_number): ?>
                                                    <div class="d-flex flex-column gap-1">
                                                        <small class="text-muted"><?php echo htmlspecialchars($order->carrier); ?></small>
                                                        <small class="fw-bold"><?php echo htmlspecialchars($order->tracking_number); ?></small>
                                                        <?php if ($order->tracking_url): ?>
                                                            <a href="<?php echo htmlspecialchars($order->tracking_url); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-truck me-1"></i>Track
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">No tracking</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo date('M j, Y H:i', strtotime($order->created_at)); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo base_url('customer/order_details/' . $order->id); ?>" 
                                                       class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
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
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="empty-state">
                                <i class="fas fa-shopping-bag"></i>
                                <h4>No Orders Yet</h4>
                                <p class="text-muted">You haven't placed any orders yet.</p>
                                <a href="<?php echo base_url('customer/products'); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Place Your First Order
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

<?php $this->load->view('customer/common/footer'); ?> 