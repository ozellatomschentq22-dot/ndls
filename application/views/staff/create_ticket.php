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
                    <i class="fas fa-plus me-2"></i>Create Support Ticket
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('staff/dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url('staff/tickets'); ?>">Support Tickets</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Ticket</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/tickets'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Tickets
                    </a>
                </div>
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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>Ticket Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('staff/create_ticket'); ?>
                            
                            <!-- Customer Selection -->
                            <div class="mb-3">
                                <label for="customer_id" class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-select" required>
                                    <option value="">Select a customer...</option>
                                    <?php foreach ($customers as $cust): ?>
                                        <option value="<?php echo $cust->id; ?>" <?php echo ($customer && $customer->id == $cust->id) ? 'selected' : set_select('customer_id', $cust->id); ?>>
                                            <?php echo htmlspecialchars($cust->first_name . ' ' . $cust->last_name . ' (' . $cust->email . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('customer_id', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <!-- Subject -->
                            <div class="mb-3">
                                <label for="subject" class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control" required placeholder="Brief description of the issue" value="<?php echo set_value('subject'); ?>">
                                <?php echo form_error('subject', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <!-- Category -->
                            <div class="mb-3">
                                <label for="category" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="">Select a category...</option>
                                    <option value="general" <?php echo set_select('category', 'general'); ?>>General Inquiry</option>
                                    <option value="order" <?php echo set_select('category', 'order'); ?>>Order Related</option>
                                    <option value="payment" <?php echo set_select('category', 'payment'); ?>>Payment Issue</option>
                                    <option value="technical" <?php echo set_select('category', 'technical'); ?>>Technical Problem</option>
                                    <option value="refund" <?php echo set_select('category', 'refund'); ?>>Refund Request</option>
                                    <option value="other" <?php echo set_select('category', 'other'); ?>>Other</option>
                                </select>
                                <?php echo form_error('category', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <!-- Priority -->
                            <div class="mb-3">
                                <label for="priority" class="form-label fw-bold">Priority <span class="text-danger">*</span></label>
                                <select name="priority" id="priority" class="form-select" required>
                                    <option value="">Select priority level...</option>
                                    <option value="low" <?php echo set_select('priority', 'low'); ?>>Low</option>
                                    <option value="medium" <?php echo set_select('priority', 'medium'); ?>>Medium</option>
                                    <option value="high" <?php echo set_select('priority', 'high'); ?>>High</option>
                                    <option value="urgent" <?php echo set_select('priority', 'urgent'); ?>>Urgent</option>
                                </select>
                                <?php echo form_error('priority', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <!-- Message -->
                            <div class="mb-4">
                                <label for="message" class="form-label fw-bold">Initial Message <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" class="form-control" rows="6" required placeholder="Please provide detailed information about the issue..."><?php echo set_value('message'); ?></textarea>
                                <?php echo form_error('message', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <!-- Hidden redirect URL -->
                            <?php if (isset($redirect_url) && $redirect_url): ?>
                                <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($redirect_url); ?>">
                            <?php endif; ?>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Create Ticket
                                </button>
                                <a href="<?php echo isset($redirect_url) && $redirect_url ? $redirect_url : base_url('staff/tickets'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Ticket Guidelines
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Priority Levels:</h6>
                            <ul class="list-unstyled small">
                                <li><span class="badge bg-secondary">Low</span> - General inquiries, non-urgent issues</li>
                                <li><span class="badge bg-info">Medium</span> - Standard support requests</li>
                                <li><span class="badge bg-warning">High</span> - Important issues requiring attention</li>
                                <li><span class="badge bg-danger">Urgent</span> - Critical issues requiring immediate action</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Categories:</h6>
                            <ul class="list-unstyled small">
                                <li><strong>General Inquiry</strong> - General questions and information</li>
                                <li><strong>Order Related</strong> - Order status, modifications, cancellations</li>
                                <li><strong>Payment Issue</strong> - Payment problems, refunds, billing</li>
                                <li><strong>Technical Problem</strong> - Website issues, account problems</li>
                                <li><strong>Refund Request</strong> - Return and refund requests</li>
                                <li><strong>Other</strong> - Any other issues not covered above</li>
                            </ul>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tip:</strong> Be specific and detailed in your initial message to help the customer understand the issue and provide a quick resolution.
                        </div>
                    </div>
                </div>

                <?php if (isset($customer) && $customer): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Name:</strong> <?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?>
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> <?php echo htmlspecialchars($customer->email); ?>
                        </div>
                        <?php if ($customer->phone): ?>
                        <div class="mb-2">
                            <strong>Phone:</strong> <?php echo htmlspecialchars($customer->phone); ?>
                        </div>
                        <?php endif; ?>
                        <div class="mb-2">
                            <strong>Status:</strong> 
                            <span class="badge bg-<?php echo $customer->status === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($customer->status); ?>
                            </span>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo base_url('staff/view_customer/' . $customer->id); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>View Customer Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-resize textarea
document.getElementById('message').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

// Set minimum date for date inputs
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.setAttribute('min', today);
    });
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 