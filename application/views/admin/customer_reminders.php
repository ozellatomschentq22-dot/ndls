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
    
    .priority-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .reminder-actions {
        min-width: 200px;
    }
    
    .reminder-content {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-bell me-2"></i>Customer Reminders
                </h1>
                <p class="text-muted mb-0">Manage notes and reminders for customers</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('admin/add_customer_reminder'); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Reminder
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-2 mb-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['total']; ?></h4>
                                <small class="text-muted">Total Reminders</small>
                            </div>
                            <div>
                                <i class="fas fa-bell fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo isset($stats['active']) ? $stats['active'] : 0; ?></h4>
                                <small class="text-muted">Active</small>
                            </div>
                            <div>
                                <i class="fas fa-clock fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo isset($stats['completed']) ? $stats['completed'] : 0; ?></h4>
                                <small class="text-muted">Completed</small>
                            </div>
                            <div>
                                <i class="fas fa-check-circle fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['overdue']; ?></h4>
                                <small class="text-muted">Overdue</small>
                            </div>
                            <div>
                                <i class="fas fa-exclamation-triangle fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['due_today']; ?></h4>
                                <small class="text-muted">Due Today</small>
                            </div>
                            <div>
                                <i class="fas fa-calendar-day fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['due_this_week']; ?></h4>
                                <small class="text-muted">Due This Week</small>
                            </div>
                            <div>
                                <i class="fas fa-calendar-week fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo base_url('admin/customer_reminders'); ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo htmlspecialchars($search_term); ?>" 
                               placeholder="Title, content, customer name...">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="active" <?php echo $current_status === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="completed" <?php echo $current_status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="archived" <?php echo $current_status === 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="">All Priorities</option>
                            <option value="low" <?php echo $current_priority === 'low' ? 'selected' : ''; ?>>Low</option>
                            <option value="medium" <?php echo $current_priority === 'medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="high" <?php echo $current_priority === 'high' ? 'selected' : ''; ?>>High</option>
                            <option value="urgent" <?php echo $current_priority === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="<?php echo base_url('admin/customer_reminders'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reminders Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>All Reminders
                    <span class="badge bg-secondary ms-2"><?php echo count($reminders); ?></span>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($reminders)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Created</th>
                                    <th>Customer</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reminders as $reminder): ?>
                                    <tr>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($reminder->created_at)); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('admin/view_customer/' . $reminder->customer_id); ?>" 
                                               class="text-decoration-none">
                                                <strong><?php echo htmlspecialchars($reminder->customer_first_name . ' ' . $reminder->customer_last_name); ?></strong>
                                            </a>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($reminder->title); ?></strong>
                                        </td>
                                        <td>
                                            <div class="reminder-content" title="<?php echo htmlspecialchars($reminder->content); ?>">
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
                                            <span class="badge bg-<?php echo $color; ?> priority-badge">
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
                                        <td class="reminder-actions">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo base_url('admin/edit_customer_reminder/' . $reminder->id); ?>" 
                                                   class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($reminder->status === 'active'): ?>
                                                    <a href="<?php echo base_url('admin/mark_reminder_completed/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                                                       class="btn btn-outline-success" title="Mark Completed"
                                                       onclick="return confirm('Mark this reminder as completed?')">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <a href="<?php echo base_url('admin/mark_reminder_archived/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                                                       class="btn btn-outline-secondary" title="Archive"
                                                       onclick="return confirm('Archive this reminder?')">
                                                        <i class="fas fa-archive"></i>
                                                    </a>
                                                <?php elseif ($reminder->status === 'completed'): ?>
                                                    <a href="<?php echo base_url('admin/reactivate_reminder/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                                                       class="btn btn-outline-warning" title="Reactivate"
                                                       onclick="return confirm('Reactivate this reminder?')">
                                                        <i class="fas fa-redo"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?php echo base_url('admin/delete_customer_reminder/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                                                   class="btn btn-outline-danger" title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this reminder?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No reminders found</h5>
                        <p class="text-muted">Create your first customer reminder to get started.</p>
                        <a href="<?php echo base_url('admin/add_customer_reminder'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Reminder
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pagination -->
        <?php if (isset($pagination) && $total_reminders > $per_page): ?>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing <?php echo (($current_page - 1) * $per_page) + 1; ?> to 
                        <?php echo min($current_page * $per_page, $total_reminders); ?> of 
                        <?php echo $total_reminders; ?> reminders
                    </div>
                    <div>
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Confirm actions
function confirmAction(message) {
    return confirm(message);
}
</script>

<?php $this->load->view('admin/includes/footer'); ?> 