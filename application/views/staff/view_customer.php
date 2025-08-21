<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-user me-2 text-primary"></i>Customer Details
                </h1>
                <p class="text-muted mb-0">View and manage customer information.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/customers'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Customers
                    </a>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="row">
            <!-- Customer Details -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <div class="avatar-title bg-primary rounded-circle" style="width: 80px; height: 80px; font-size: 2rem;">
                                    <?php echo strtoupper(substr($customer->first_name, 0, 1) . substr($customer->last_name, 0, 1)); ?>
                                </div>
                            </div>
                            <h4><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></h4>
                            <p class="text-muted">Customer ID: #<?php echo $customer->id; ?></p>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email:</label>
                                    <div>
                                        <a href="mailto:<?php echo htmlspecialchars($customer->email); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($customer->email); ?>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phone:</label>
                                    <div>
                                        <?php if (isset($customer->phone) && !empty($customer->phone)): ?>
                                            <a href="tel:<?php echo htmlspecialchars($customer->phone); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($customer->phone); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Not provided</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Username:</label>
                                    <div><?php echo htmlspecialchars($customer->username); ?></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status:</label>
                                    <div>
                                        <?php if ($customer->status == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Joined:</label>
                                    <div><?php echo date('F j, Y', strtotime($customer->created_at)); ?></div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Last Updated:</label>
                                    <div><?php echo date('F j, Y', strtotime($customer->updated_at)); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Activity -->
            <div class="col-lg-8">
                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0"><?php echo count($customer_orders); ?></h4>
                                        <small>Total Orders</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-shopping-bag fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0"><?php echo count($customer_tickets); ?></h4>
                                        <small>Support Tickets</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-headset fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0"><?php echo count($customer_reminders); ?></h4>
                                        <small>Reminders</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-bell fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-bag me-2"></i>Recent Orders
                        </h5>
                        <a href="<?php echo base_url('staff/orders'); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($customer_orders)): ?>
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
                                        <?php foreach (array_slice($customer_orders, 0, 5) as $order): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_order/' . $order->id); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($order->order_number); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($order->product_name); ?></td>
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
                                            <td><?php echo date('M j, Y', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_order/' . $order->id); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-shopping-bag fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No orders found for this customer.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Support Tickets -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-headset me-2"></i>Recent Support Tickets
                        </h5>
                        <a href="<?php echo base_url('staff/tickets'); ?>" class="btn btn-sm btn-outline-success">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($customer_tickets)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($customer_tickets, 0, 5) as $ticket): ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_ticket/' . $ticket->id); ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($ticket->ticket_number); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($ticket->subject); ?></td>
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
                                            <td><?php echo date('M j, Y', strtotime($ticket->created_at)); ?></td>
                                            <td>
                                                <a href="<?php echo base_url('staff/view_ticket/' . $ticket->id); ?>" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-headset fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No support tickets found for this customer.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Customer Reminders -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>Customer Reminders
                        </h5>
                        <a href="<?php echo base_url('staff/add_customer_reminder'); ?>" class="btn btn-sm btn-outline-warning">Add Reminder</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($customer_reminders)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Due Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($customer_reminders, 0, 5) as $reminder): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($reminder->title); ?></td>
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
                                                <a href="<?php echo base_url('staff/edit_customer_reminder/' . $reminder->id); ?>" class="btn btn-sm btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-bell fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No reminders found for this customer.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('staff/includes/footer'); ?> 