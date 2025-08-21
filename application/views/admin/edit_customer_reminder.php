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
    
    .reminder-info {
        background-color: #f8f9fa;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-edit me-2"></i>Edit Customer Reminder
                </h1>
                <p class="text-muted mb-0">Update reminder details and status</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('admin/customer_reminders'); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Reminders
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

        <!-- Reminder Information -->
        <div class="reminder-info">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-2"><i class="fas fa-user me-2"></i>Customer</h6>
                    <p class="mb-1">
                        <a href="<?php echo base_url('admin/view_customer/' . $reminder->customer_id); ?>" 
                           class="text-decoration-none">
                            <strong><?php echo htmlspecialchars($reminder->customer_first_name . ' ' . $reminder->customer_last_name); ?></strong>
                        </a>
                    </p>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-2"><i class="fas fa-user-tie me-2"></i>Created By</h6>
                    <p class="mb-1">
                        <strong><?php echo htmlspecialchars($reminder->admin_first_name . ' ' . $reminder->admin_last_name); ?></strong>
                    </p>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <h6 class="mb-2"><i class="fas fa-calendar me-2"></i>Created Date</h6>
                    <p class="mb-1"><?php echo date('F j, Y \a\t g:i A', strtotime($reminder->created_at)); ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-2"><i class="fas fa-clock me-2"></i>Last Updated</h6>
                    <p class="mb-1"><?php echo date('F j, Y \a\t g:i A', strtotime($reminder->updated_at)); ?></p>
                </div>
            </div>
        </div>

        <!-- Edit Reminder Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Update Reminder Details
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open('admin/edit_customer_reminder/' . $reminder->id); ?>
                    
                    <!-- Title -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo form_error('title') ? 'is-invalid' : ''; ?>" 
                                   id="title" name="title" 
                                   value="<?php echo set_value('title', $reminder->title); ?>" 
                                   placeholder="Enter reminder title" required maxlength="255">
                            <?php if (form_error('title')): ?>
                                <div class="invalid-feedback"><?php echo form_error('title'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php echo form_error('content') ? 'is-invalid' : ''; ?>" 
                                      id="content" name="content" rows="5" 
                                      placeholder="Enter detailed reminder content..." required><?php echo set_value('content', $reminder->content); ?></textarea>
                            <?php if (form_error('content')): ?>
                                <div class="invalid-feedback"><?php echo form_error('content'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Priority and Status -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select <?php echo form_error('priority') ? 'is-invalid' : ''; ?>" 
                                    id="priority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low" <?php echo set_select('priority', 'low', $reminder->priority === 'low'); ?>>Low</option>
                                <option value="medium" <?php echo set_select('priority', 'medium', $reminder->priority === 'medium'); ?>>Medium</option>
                                <option value="high" <?php echo set_select('priority', 'high', $reminder->priority === 'high'); ?>>High</option>
                                <option value="urgent" <?php echo set_select('priority', 'urgent', $reminder->priority === 'urgent'); ?>>Urgent</option>
                            </select>
                            <?php if (form_error('priority')): ?>
                                <div class="invalid-feedback"><?php echo form_error('priority'); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?php echo form_error('status') ? 'is-invalid' : ''; ?>" 
                                    id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active" <?php echo set_select('status', 'active', $reminder->status === 'active'); ?>>Active</option>
                                <option value="completed" <?php echo set_select('status', 'completed', $reminder->status === 'completed'); ?>>Completed</option>
                                <option value="archived" <?php echo set_select('status', 'archived', $reminder->status === 'archived'); ?>>Archived</option>
                            </select>
                            <?php if (form_error('status')): ?>
                                <div class="invalid-feedback"><?php echo form_error('status'); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">Due Date (Optional)</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" 
                                   value="<?php echo set_value('due_date', $reminder->due_date); ?>">
                            <small class="form-text text-muted">Leave empty if no specific due date is required</small>
                        </div>
                    </div>

                    <!-- Priority Help Text -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Priority Guidelines:</h6>
                                <ul class="mb-0">
                                    <li><strong>Low:</strong> General notes, follow-ups that can wait</li>
                                    <li><strong>Medium:</strong> Standard reminders, regular follow-ups</li>
                                    <li><strong>High:</strong> Important matters requiring attention soon</li>
                                    <li><strong>Urgent:</strong> Critical issues requiring immediate attention</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Status Help Text -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Status Guidelines:</h6>
                                <ul class="mb-0">
                                    <li><strong>Active:</strong> Reminder is currently active and needs attention</li>
                                    <li><strong>Completed:</strong> Task has been completed successfully</li>
                                    <li><strong>Archived:</strong> Reminder is no longer relevant but kept for reference</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden redirect URL -->
                    <input type="hidden" name="redirect_url" value="<?php echo $redirect_url ?: 'admin/customer_reminders'; ?>">

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo base_url('admin/customer_reminders'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <div>
                                    <a href="<?php echo base_url('admin/delete_customer_reminder/' . $reminder->id . '?redirect_url=' . urlencode('admin/customer_reminders')); ?>" 
                                       class="btn btn-outline-danger me-2"
                                       onclick="return confirm('Are you sure you want to delete this reminder? This action cannot be undone.')">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Reminder
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if ($reminder->status === 'active'): ?>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo base_url('admin/mark_reminder_completed/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                               class="btn btn-success btn-sm w-100"
                               onclick="return confirm('Mark this reminder as completed?')">
                                <i class="fas fa-check me-1"></i>Mark Completed
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo base_url('admin/mark_reminder_archived/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                               class="btn btn-secondary btn-sm w-100"
                               onclick="return confirm('Archive this reminder?')">
                                <i class="fas fa-archive me-1"></i>Archive
                            </a>
                        </div>
                    <?php elseif ($reminder->status === 'completed'): ?>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo base_url('admin/reactivate_reminder/' . $reminder->id . '?redirect_url=' . urlencode(current_url())); ?>" 
                               class="btn btn-warning btn-sm w-100"
                               onclick="return confirm('Reactivate this reminder?')">
                                <i class="fas fa-redo me-1"></i>Reactivate
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-3 mb-2">
                        <a href="<?php echo base_url('admin/view_customer/' . $reminder->customer_id); ?>" 
                           class="btn btn-info btn-sm w-100">
                            <i class="fas fa-user me-1"></i>View Customer
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?php echo base_url('admin/add_customer_reminder/' . $reminder->customer_id . '?redirect_url=' . urlencode(current_url())); ?>" 
                           class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-plus me-1"></i>Add New Reminder
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea
    const textarea = document.getElementById('content');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        // Trigger resize on load
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }
    
    // Set minimum date for due date
    const dueDateInput = document.getElementById('due_date');
    if (dueDateInput) {
        dueDateInput.min = new Date().toISOString().split('T')[0];
    }
});
</script>

<?php $this->load->view('admin/includes/footer'); ?> 