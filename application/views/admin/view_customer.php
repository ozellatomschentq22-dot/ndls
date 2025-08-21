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
    
    .table-responsive {
        width: 100% !important;
    }
    
    .table {
        width: 100% !important;
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
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-user me-2"></i>Customer Details
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/customers'); ?>">Customers</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></li>
                    </ol>
                </nav>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/edit_customer/' . $customer->id); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit Customer
                    </a>
                    <a href="<?php echo base_url('admin/customer_pricing/' . $customer->id); ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-tags me-1"></i>Pricing
                    </a>
                    <a href="<?php echo base_url('admin/view_customer_messages/' . $customer->id); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-comments me-1"></i>Messages
                    </a>
                    <a href="<?php echo base_url('admin/add_customer_reminder/' . $customer->id . '?redirect_url=' . urlencode(current_url())); ?>" class="btn btn-indigo btn-sm">
                        <i class="fas fa-bell me-1"></i>Add Reminder
                    </a>
                    <a href="<?php echo base_url('admin/create_ticket?customer_id=' . $customer->id . '&redirect_url=' . urlencode(current_url())); ?>" class="btn btn-danger btn-sm">
                        <i class="fas fa-ticket-alt me-1"></i>Create Ticket
                    </a>
                    <a href="<?php echo base_url('admin/customers'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="text-primary"><?php echo count($orders); ?></h4>
                        <small class="text-muted">Total Orders</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="text-success">$<?php echo number_format(array_sum(array_column($orders, 'total_amount')), 2); ?></h4>
                        <small class="text-muted">Total Spent</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="text-info"><?php echo count($addresses); ?></h4>
                        <small class="text-muted">Addresses</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h4 class="<?php echo ($wallet && $wallet->balance < 0) ? 'text-danger' : 'text-success'; ?>">
                            <?php echo $wallet ? '$' . number_format($wallet->balance, 2) : '$0.00'; ?>
                        </h4>
                        <small class="text-muted">Wallet Balance</small>
                    </div>
                </div>
            </div>

        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Customer Profile -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>Profile Information
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                            <?php echo strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)); ?>
                        </div>
                        <h4 class="mb-3"><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></h4>
                        
                        <div class="text-start">
                            <div class="mb-3">
                                <div class="fw-bold text-muted mb-1">Customer ID</div>
                                <div>#<?php echo $customer->id; ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="fw-bold text-muted mb-1">Email Address</div>
                                <div>
                                    <a href="mailto:<?php echo htmlspecialchars($customer->email); ?>">
                                        <?php echo htmlspecialchars($customer->email); ?>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="fw-bold text-muted mb-1">Phone Number</div>
                                <div>
                                    <?php if (isset($customer->phone) && !empty($customer->phone)): ?>
                                        <a href="tel:<?php echo htmlspecialchars($customer->phone); ?>">
                                            <?php echo htmlspecialchars($customer->phone); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not provided</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="fw-bold text-muted mb-1">Account Status</div>
                                <div>
                                    <?php if (isset($customer->status) && $customer->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="fw-bold text-muted mb-1">Customer Type</div>
                                <div>
                                    <?php if (isset($customer->customer_type)): ?>
                                        <span class="badge bg-info"><?php echo ucfirst($customer->customer_type); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-info">Regular</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="fw-bold text-muted mb-1">Member Since</div>
                                <div><?php echo date('F j, Y', strtotime($customer->created_at)); ?></div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="fw-bold text-muted mb-1">Last Updated</div>
                                <div><?php echo date('F j, Y', strtotime($customer->updated_at)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <!-- Wallet Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-wallet me-2"></i>Wallet Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($wallet): ?>
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <h3 class="<?php echo $wallet->balance < 0 ? 'text-danger' : 'text-success'; ?>">
                                            $<?php echo number_format($wallet->balance, 2); ?>
                                        </h3>
                                        <div class="text-muted">Current Balance</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-grid gap-2">
                                        <a href="<?php echo base_url('admin/credit_wallet/' . $customer->id); ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-plus me-1"></i>Credit Wallet
                                        </a>
                                        <a href="<?php echo base_url('admin/debit_wallet/' . $customer->id); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-minus me-1"></i>Debit Wallet
                                        </a>
                                        <a href="<?php echo base_url('admin/wallet_transactions/' . $customer->id); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-history me-1"></i>View Transactions
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-wallet fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No wallet found for this customer.</p>
                                <a href="<?php echo base_url('admin/credit_wallet/' . $customer->id); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i>Create Wallet
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>Recent Orders
                        </h5>
                        <span class="badge bg-primary"><?php echo count($orders); ?> total</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($orders)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Product</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($order->order_number); ?></strong>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?php echo htmlspecialchars($order->product_name); ?></div>
                                                <?php if (isset($order->brand) && !empty($order->brand)): ?>
                                                    <small class="text-muted"><?php echo htmlspecialchars($order->brand); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">$<?php echo number_format($order->total_amount, 2); ?></span>
                                            </td>
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
                                                <div><?php echo date('M j, Y', strtotime($order->created_at)); ?></div>
                                                <small class="text-muted"><?php echo date('g:i A', strtotime($order->created_at)); ?></small>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('admin/view_order/' . $order->id); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (count($orders) > 5): ?>
                                <div class="text-center mt-3">
                                    <a href="<?php echo base_url('admin/orders?user_id=' . $customer->id); ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-list me-1"></i>View All Orders
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No orders found for this customer.</p>
                                <a href="<?php echo base_url('admin/create_order?user_id=' . $customer->id); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i>Create Order
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Reminders -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-bell me-2"></i>Customer Reminders
                </h5>
                <div>
                    <span class="badge bg-indigo me-2"><?php echo count($reminders); ?> total</span>
                    <a href="<?php echo base_url('admin/add_customer_reminder/' . $customer->id . '?redirect_url=' . urlencode(current_url())); ?>" class="btn btn-indigo btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Reminder
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($reminders)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Created By</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reminders as $reminder): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($reminder->title); ?></strong>
                                    </td>
                                    <td>
                                        <div class="small" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($reminder->content); ?>">
                                            <?php echo htmlspecialchars($reminder->content); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $priority_colors = [
                                            'low' => 'secondary',
                                            'medium' => 'info',
                                            'high' => 'warning',
                                            'urgent' => 'danger'
                                        ];
                                        $color = isset($priority_colors[$reminder->priority]) ? $priority_colors[$reminder->priority] : 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $color; ?>">
                                            <?php echo ucfirst($reminder->priority); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $status_colors = [
                                            'active' => 'success',
                                            'completed' => 'primary',
                                            'archived' => 'secondary'
                                        ];
                                        $color = isset($status_colors[$reminder->status]) ? $status_colors[$reminder->status] : 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $color; ?>">
                                            <?php echo ucfirst($reminder->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($reminder->due_date): ?>
                                            <small class="<?php echo strtotime($reminder->due_date) < time() ? 'text-danger' : 'text-muted'; ?>">
                                                <?php echo date('M j, Y', strtotime($reminder->due_date)); ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($reminder->admin_first_name . ' ' . $reminder->admin_last_name); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div><?php echo date('M j, Y', strtotime($reminder->created_at)); ?></div>
                                        <small class="text-muted"><?php echo date('g:i A', strtotime($reminder->created_at)); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo base_url('admin/edit_customer_reminder/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($reminder->status === 'active'): ?>
                                                <a href="<?php echo base_url('admin/mark_reminder_completed/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                                                   class="btn btn-outline-success" title="Mark Completed"
                                                   onclick="return confirm('Mark this reminder as completed?')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php elseif ($reminder->status === 'completed'): ?>
                                                <a href="<?php echo base_url('admin/reactivate_reminder/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                                                   class="btn btn-outline-warning" title="Reactivate"
                                                   onclick="return confirm('Reactivate this reminder?')">
                                                    <i class="fas fa-redo"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No reminders found for this customer.</p>
                        <a href="<?php echo base_url('admin/add_customer_reminder/' . $customer->id . '?redirect_url=' . urlencode(current_url())); ?>" class="btn btn-indigo btn-sm">
                            <i class="fas fa-plus me-1"></i>Add First Reminder
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Lead History -->
        <?php if (!empty($leads)): ?>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>Lead History
                </h5>
                <span class="badge bg-warning"><?php echo count($leads); ?> records</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Product Interest</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td>
                                    <div><?php echo date('M j, Y', strtotime($lead->created_at)); ?></div>
                                    <small class="text-muted"><?php echo date('g:i A', strtotime($lead->created_at)); ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($lead->first_name . ' ' . $lead->last_name); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($lead->email); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($lead->phone); ?></td>
                                <td>
                                    <div class="small">
                                        <?php echo htmlspecialchars($lead->address_line1); ?><br>
                                        <?php echo htmlspecialchars($lead->city . ', ' . $lead->state . ' ' . $lead->postal_code); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($lead->product_interest)): ?>
                                        <div class="small"><?php echo htmlspecialchars($lead->product_interest); ?></div>
                                    <?php else: ?>
                                        <span class="text-muted">Not specified</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($lead->payment_method)): ?>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($lead->payment_method); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">Not specified</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $status_colors = [
                                        'new' => 'warning',
                                        'contacted' => 'info',
                                        'qualified' => 'primary',
                                        'converted' => 'success',
                                        'lost' => 'danger'
                                    ];
                                    $color = isset($status_colors[$lead->status]) ? $status_colors[$lead->status] : 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $color; ?>">
                                        <?php echo ucfirst($lead->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('admin/view_lead/' . $lead->id); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Addresses -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-map-marker-alt me-2"></i>Saved Addresses
                </h5>
                <span class="badge bg-primary"><?php echo count($addresses); ?> addresses</span>
            </div>
            <div class="card-body">
                <?php if (!empty($addresses)): ?>
                    <div class="row">
                        <?php foreach ($addresses as $address): ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-home me-1 text-primary"></i>
                                            <?php echo htmlspecialchars($address->address_name); ?>
                                        </h6>
                                        <?php if ($address->is_default): ?>
                                            <span class="badge bg-primary">Default</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="address-details">
                                        <div class="fw-bold mb-2"><?php echo htmlspecialchars($address->full_name); ?></div>
                                        <div class="text-muted small">
                                            <div><?php echo htmlspecialchars($address->address_line1); ?></div>
                                            <?php if (!empty($address->address_line2)): ?>
                                                <div><?php echo htmlspecialchars($address->address_line2); ?></div>
                                            <?php endif; ?>
                                            <div><?php echo htmlspecialchars($address->city . ', ' . $address->state . ' ' . $address->postal_code); ?></div>
                                            <div><?php echo htmlspecialchars($address->country); ?></div>
                                            <?php if (!empty($address->phone)): ?>
                                                <div class="mt-2">
                                                    <i class="fas fa-phone me-1"></i>
                                                    <?php echo htmlspecialchars($address->phone); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No addresses found for this customer.</p>
                        <a href="<?php echo base_url('admin/create_customer_address?user_id=' . $customer->id); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Address
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 