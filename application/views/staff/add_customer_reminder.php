<?php $this->load->view('staff/includes/header'); ?>

<style>
    .main-content {
        margin-left: 280px;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }
    
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
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
                    <i class="fas fa-plus me-2"></i>Add Customer Reminder
                </h1>
                <p class="text-muted mb-0">Create a new reminder or note for a customer</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('staff/customer_reminders'); ?>" class="btn btn-outline-secondary btn-sm">
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

        <!-- Add Reminder Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Reminder Details
                </h5>
            </div>
            <div class="card-body">
                <?php echo form_open('staff/add_customer_reminder' . (isset($selected_customer_id) && $selected_customer_id ? '/' . $selected_customer_id : '')); ?>
                    
                    <!-- Customer Selection -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select <?php echo form_error('customer_id') ? 'is-invalid' : ''; ?>" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?php echo $customer->id; ?>" 
                                            <?php echo (isset($selected_customer_id) && $selected_customer_id == $customer->id || set_value('customer_id') == $customer->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name . ' (' . $customer->email . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (form_error('customer_id')): ?>
                                <div class="invalid-feedback"><?php echo form_error('customer_id'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo form_error('title') ? 'is-invalid' : ''; ?>" 
                                   id="title" name="title" value="<?php echo set_value('title'); ?>" 
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
                                      placeholder="Enter detailed reminder content..." required><?php echo set_value('content'); ?></textarea>
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
                                <option value="low" <?php echo set_select('priority', 'low'); ?>>Low</option>
                                <option value="medium" <?php echo set_select('priority', 'medium', TRUE); ?>>Medium</option>
                                <option value="high" <?php echo set_select('priority', 'high'); ?>>High</option>
                                <option value="urgent" <?php echo set_select('priority', 'urgent'); ?>>Urgent</option>
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
                                <option value="active" <?php echo set_select('status', 'active', TRUE); ?>>Active</option>
                                <option value="completed" <?php echo set_select('status', 'completed'); ?>>Completed</option>
                                <option value="archived" <?php echo set_select('status', 'archived'); ?>>Archived</option>
                            </select>
                            <?php if (form_error('status')): ?>
                                <div class="invalid-feedback"><?php echo form_error('status'); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">Due Date (Optional)</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" 
                                   value="<?php echo set_value('due_date'); ?>" 
                                   min="<?php echo date('Y-m-d'); ?>">
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

                    <!-- Hidden redirect URL -->
                    <input type="hidden" name="redirect_url" value="<?php echo isset($redirect_url) ? $redirect_url : 'staff/customer_reminders'; ?>">

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="<?php echo base_url('staff/customer_reminders'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Reminder
                                </button>
                            </div>
                        </div>
                    </div>

                <?php echo form_close(); ?>
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
    }
    
    // Set minimum date for due date
    const dueDateInput = document.getElementById('due_date');
    if (dueDateInput) {
        dueDateInput.min = new Date().toISOString().split('T')[0];
    }
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 