<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-shopping-bag me-2 text-success"></i>Orders
                </h1>
                <p class="text-muted mb-0">Manage all customer orders and track their status.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/create_order'); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Create Order
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search orders...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="allOrders">All</button>
                    <button type="button" class="btn btn-outline-warning" id="pendingOrders">Pending</button>
                    <button type="button" class="btn btn-outline-info" id="processingOrders">Processing</button>
                    <button type="button" class="btn btn-outline-primary" id="shippedOrders">Shipped</button>
                    <button type="button" class="btn btn-outline-success" id="deliveredOrders">Delivered</button>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Product</th>
                                    <th>Brand</th>
                                    <th>Strength</th>
                                    <th>Variant</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Tracking</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo base_url('staff/view_order/' . $order->id); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($order->order_number); ?>
                                        </a>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($order->created_at)); ?></td>
                                    <td>
                                        <a href="<?php echo base_url('staff/view_customer/' . $order->user_id); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($order->first_name . ' ' . $order->last_name); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($order->product_name); ?></td>
                                    <td><?php echo htmlspecialchars($order->brand); ?></td>
                                    <td><?php echo htmlspecialchars($order->strength); ?></td>
                                    <td><?php echo htmlspecialchars($order->product_quantity); ?> pills</td>
                                    <td>$<?php echo number_format($order->total_amount, 2); ?></td>
                                    <td>
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
                                        <span class="badge bg-<?php echo $color; ?>">
                                            <?php echo ucfirst($order->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($order->tracking_number)): ?>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($order->tracking_number); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('staff/view_order/' . $order->id); ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($order->status === 'pending'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        onclick="updateStatus(<?php echo $order->id; ?>, 'processing')" title="Mark Processing">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                            <?php elseif ($order->status === 'processing'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="updateStatus(<?php echo $order->id; ?>, 'shipped')" title="Mark Shipped">
                                                    <i class="fas fa-truck"></i>
                                                </button>
                                            <?php elseif ($order->status === 'shipped'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="updateStatus(<?php echo $order->id; ?>, 'delivered')" title="Mark Delivered">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (in_array($order->status, ['pending', 'processing'])): ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="updateStatus(<?php echo $order->id; ?>, 'cancelled')" title="Cancel Order">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($pagination)): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo $pagination; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No orders found</h5>
                        <p class="text-muted">There are no orders in the system yet.</p>
                        <a href="<?php echo base_url('staff/create_order'); ?>" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Create First Order
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    
    searchBtn.addEventListener('click', function() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            window.location.href = '<?php echo base_url('staff/orders'); ?>?search=' + encodeURIComponent(searchTerm);
        }
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
    
    // Filter buttons
    document.getElementById('allOrders').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/orders'); ?>';
    });
    
    document.getElementById('pendingOrders').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/orders'); ?>?status=pending';
    });
    
    document.getElementById('processingOrders').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/orders'); ?>?status=processing';
    });
    
    document.getElementById('shippedOrders').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/orders'); ?>?status=shipped';
    });
    
    document.getElementById('deliveredOrders').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/orders'); ?>?status=delivered';
    });
});

// Function to update order status
function updateStatus(orderId, status) {
    const statusMessages = {
        'processing': 'Mark order as processing?',
        'shipped': 'Mark order as shipped?',
        'delivered': 'Mark order as delivered?',
        'cancelled': 'Cancel this order? This action cannot be undone.'
    };
    
    const message = statusMessages[status] || 'Update order status?';
    
    if (confirm(message)) {
        window.location.href = '<?php echo base_url('staff/update_order_status/'); ?>' + orderId + '/' + status;
    }
}
</script>

<?php $this->load->view('staff/includes/footer'); ?> 