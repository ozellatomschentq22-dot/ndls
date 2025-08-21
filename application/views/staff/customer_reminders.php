<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-bell me-2 text-warning"></i>Customer Reminders
                </h1>
                <p class="text-muted mb-0">Manage customer reminders and notes.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/add_customer_reminder'); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Reminder
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $active_reminders; ?></h4>
                                <small>Active</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-bell fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $completed_reminders; ?></h4>
                                <small>Completed</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $archived_reminders; ?></h4>
                                <small>Archived</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-archive fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $overdue_reminders; ?></h4>
                                <small>Overdue</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search reminders...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="allReminders">All</button>
                    <button type="button" class="btn btn-outline-success" id="activeReminders">Active</button>
                    <button type="button" class="btn btn-outline-primary" id="completedReminders">Completed</button>
                    <button type="button" class="btn btn-outline-danger" id="overdueReminders">Overdue</button>
                </div>
            </div>
        </div>

        <!-- Reminders Table -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($reminders)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Title</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reminders as $reminder): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo base_url('staff/view_customer/' . $reminder->customer_id); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($reminder->customer_first_name . ' ' . $reminder->customer_last_name); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($reminder->title); ?></div>
                                        <small class="text-muted"><?php echo substr(htmlspecialchars($reminder->content), 0, 50) . '...'; ?></small>
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
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('staff/edit_customer_reminder/' . $reminder->id); ?>" class="btn btn-sm btn-outline-warning" title="Edit Reminder">
                                                <i class="fas fa-edit"></i>
                                            </a>
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
                        <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No reminders found</h5>
                        <p class="text-muted">There are no customer reminders in the system yet.</p>
                        <a href="<?php echo base_url('staff/add_customer_reminder'); ?>" class="btn btn-warning">
                            <i class="fas fa-plus me-1"></i>Add First Reminder
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
            window.location.href = '<?php echo base_url('staff/customer_reminders'); ?>?search=' + encodeURIComponent(searchTerm);
        }
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
    
    // Filter buttons
    document.getElementById('allReminders').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/customer_reminders'); ?>';
    });
    
    document.getElementById('activeReminders').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/customer_reminders'); ?>?status=active';
    });
    
    document.getElementById('completedReminders').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/customer_reminders'); ?>?status=completed';
    });
    
    document.getElementById('overdueReminders').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/customer_reminders'); ?>?status=overdue';
    });
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 