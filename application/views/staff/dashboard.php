<?php $this->load->view('staff/includes/header'); ?>

<style>
    .stats-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
    }
    
    .stats-card.customers {
        border-left-color: #007bff;
    }
    
    .stats-card.orders {
        border-left-color: #28a745;
    }
    
    .stats-card.leads {
        border-left-color: #ffc107;
    }
    
    .stats-card.products {
        border-left-color: #17a2b8;
    }
    
    .stats-card.tickets {
        border-left-color: #dc3545;
    }
    
    .stats-card.wallet {
        border-left-color: #6f42c1;
    }
    
    .stats-card.recharge {
        border-left-color: #fd7e14;
    }
    
    .stats-card.reminders {
        border-left-color: #6610f2;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: bold;
    }
    
    .stats-label {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .activity-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .table td {
        vertical-align: middle !important;
        font-size: 0.875rem !important;
    }
    
    @media (max-width: 768px) {
        .main-content {
            width: 100% !important;
            margin-left: 0 !important;
            padding: 1rem !important;
        }
        
        .stats-number {
            font-size: 1.5rem;
        }
    }
</style>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-tachometer-alt me-2 text-success"></i>Staff Dashboard
                </h1>
                <p class="text-muted mb-0">Welcome back, <?php echo $user['first_name']; ?>! Here's what's happening today.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/create_order'); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Create Order
                    </a>
                    <a href="<?php echo base_url('staff/add_lead'); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-user-plus me-1"></i>Add Lead
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card customers h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-number text-primary"><?php echo $total_users; ?></div>
                                <div class="stats-label">Total Customers</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card orders h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-number text-success"><?php echo $total_orders; ?></div>
                                <div class="stats-label">Total Orders</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-bag fa-2x text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card leads h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-number text-warning"><?php echo $total_leads; ?></div>
                                <div class="stats-label">Total Leads</div>
                                <?php if ($new_leads > 0): ?>
                                    <small class="text-danger"><?php echo $new_leads; ?> new</small>
                                <?php endif; ?>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-plus fa-2x text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card tickets h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-number text-danger"><?php echo $total_tickets; ?></div>
                                <div class="stats-label">Support Tickets</div>
                                <?php if ($pending_tickets > 0): ?>
                                    <small class="text-danger"><?php echo $pending_tickets; ?> pending</small>
                                <?php endif; ?>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-headset fa-2x text-danger opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card wallet h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-number text-purple">$<?php echo number_format($wallet_summary->total_balance, 2); ?></div>
                                <div class="stats-label">Total Wallet Balance</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-purple opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card recharge h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-number text-orange"><?php echo $total_recharge_requests; ?></div>
                                <div class="stats-label">Recharge Requests</div>
                                <?php if ($pending_recharge_requests > 0): ?>
                                    <small class="text-danger"><?php echo $pending_recharge_requests; ?> pending</small>
                                <?php endif; ?>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-credit-card fa-2x text-orange opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card reminders h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="stats-number text-indigo"><?php echo $total_reminders; ?></div>
                                <div class="stats-label">Customer Reminders</div>
                                <?php if ($active_reminders > 0): ?>
                                    <small class="text-primary"><?php echo $active_reminders; ?> active</small>
                                <?php endif; ?>
                                <?php if ($overdue_reminders > 0): ?>
                                    <small class="text-danger"><?php echo $overdue_reminders; ?> overdue</small>
                                <?php endif; ?>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bell fa-2x text-indigo opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Quick Actions -->
        <div class="row">
            <!-- Recent Orders -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-bag me-2"></i>Recent Orders
                        </h5>
                        <div class="btn-group">
                            <a href="<?php echo base_url('staff/create_order'); ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-plus me-1"></i>Add Order
                            </a>
                            <a href="<?php echo base_url('staff/orders'); ?>" class="btn btn-sm btn-outline-success">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_orders)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Product</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_order/' . $order->id); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($order->order_number); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_customer/' . $order->user_id); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($order->first_name . ' ' . $order->last_name); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="small"><?php echo htmlspecialchars($order->product_name); ?></div>
                                            </td>
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
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent orders</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Customers -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>Recent Customers
                        </h5>
                        <div class="btn-group">
                            <a href="<?php echo base_url('staff/add_customer'); ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i>Add Customer
                            </a>
                            <a href="<?php echo base_url('staff/customers'); ?>" class="btn btn-sm btn-outline-success">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_customers)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_customers as $customer): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_customer/' . $customer->id); ?>" class="text-decoration-none">
                                                    <div class="fw-bold"><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></div>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($customer->email); ?></td>
                                            <td>
                                                <?php echo isset($customer->phone) && !empty($customer->phone) ? htmlspecialchars($customer->phone) : 'N/A'; ?>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($customer->created_at)); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent customers</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Leads and Support Tickets -->
        <div class="row">
            <!-- Recent Leads -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>Recent Leads
                        </h5>
                        <div class="btn-group">
                            <a href="<?php echo base_url('staff/add_lead'); ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-plus me-1"></i>Add Lead
                            </a>
                            <a href="<?php echo base_url('staff/leads'); ?>" class="btn btn-sm btn-outline-warning">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_leads)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_leads as $lead): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_lead/' . $lead->id); ?>" class="text-decoration-none">
                                                    <div class="fw-bold"><?php echo htmlspecialchars($lead->first_name . ' ' . $lead->last_name); ?></div>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($lead->email); ?></td>
                                            <td><?php echo htmlspecialchars($lead->phone); ?></td>
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
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent leads</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Support Tickets -->
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-headset me-2"></i>Recent Support Tickets
                        </h5>
                        <div class="btn-group">
                            <a href="<?php echo base_url('staff/create_ticket'); ?>" class="btn btn-sm btn-danger">
                                <i class="fas fa-plus me-1"></i>Add Ticket
                            </a>
                            <a href="<?php echo base_url('staff/tickets'); ?>" class="btn btn-sm btn-outline-danger">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_tickets)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Customer</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_tickets as $ticket): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_ticket/' . $ticket->id); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($ticket->ticket_number); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_customer/' . $ticket->user_id); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($ticket->first_name . ' ' . $ticket->last_name); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="small"><?php echo htmlspecialchars($ticket->subject); ?></div>
                                            </td>
                                            <td>
                                                <?php
                                                $status_colors = [
                                                    'open' => 'danger',
                                                    'in_progress' => 'warning',
                                                    'closed' => 'success'
                                                ];
                                                $color = isset($status_colors[$ticket->status]) ? $status_colors[$ticket->status] : 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $color; ?>">
                                                    <?php echo ucfirst(str_replace('_', ' ', $ticket->status)); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-headset fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent tickets</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Reminders -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>Recent Active Reminders
                        </h5>
                        <div class="btn-group">
                            <a href="<?php echo base_url('staff/add_customer_reminder'); ?>" class="btn btn-sm btn-indigo">
                                <i class="fas fa-plus me-1"></i>Add Reminder
                            </a>
                            <a href="<?php echo base_url('staff/customer_reminders'); ?>" class="btn btn-sm btn-outline-indigo">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_reminders)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Title</th>
                                            <th>Priority</th>
                                            <th>Due Date</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_reminders as $reminder): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_customer/' . $reminder->customer_id); ?>" class="text-decoration-none">
                                                    <strong><?php echo htmlspecialchars($reminder->customer_first_name . ' ' . $reminder->customer_last_name); ?></strong>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="small"><?php echo htmlspecialchars($reminder->title); ?></div>
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
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No active reminders</p>
                                <a href="<?php echo base_url('staff/add_customer_reminder'); ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus me-1"></i>Add Reminder
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('staff/create_order'); ?>" class="btn btn-outline-success w-100">
                                    <i class="fas fa-shopping-cart me-2"></i>Create Order
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('staff/add_lead'); ?>" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-user-plus me-2"></i>Add New Lead
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('staff/leads'); ?>" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-users me-2"></i>Manage Leads
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('staff/tickets'); ?>" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-headset me-2"></i>Support Tickets
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('staff/recharge_requests'); ?>" class="btn btn-outline-orange w-100">
                                    <i class="fas fa-credit-card me-2"></i>Recharge Requests
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('staff/customer_reminders'); ?>" class="btn btn-outline-indigo w-100">
                                    <i class="fas fa-bell me-2"></i>Customer Reminders
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('staff/wallets'); ?>" class="btn btn-outline-purple w-100">
                                    <i class="fas fa-wallet me-2"></i>Manage Wallets
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('staff/includes/footer'); ?> 