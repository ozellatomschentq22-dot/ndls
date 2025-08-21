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
                    <i class="fas fa-plus me-2"></i>Start New Message
                </h1>
                <p class="text-muted mb-0">Send a new message to a customer</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/admin_messages'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Messages
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
                            <i class="fas fa-comment me-2"></i>Message Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('staff/start_new_message' . (isset($customer) && $customer ? '/' . $customer->id : '')); ?>
                            
                            <!-- Customer Selection -->
                            <div class="mb-3">
                                <label for="customer_id" class="form-label fw-bold">Customer <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-select" required>
                                    <option value="">Select a customer...</option>
                                    <?php foreach ($customers as $cust): ?>
                                        <option value="<?php echo $cust->id; ?>" <?php echo (isset($customer) && $customer && $customer->id == $cust->id) ? 'selected' : set_select('customer_id', $cust->id); ?>>
                                            <?php echo htmlspecialchars($cust->first_name . ' ' . $cust->last_name . ' (' . $cust->email . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php echo form_error('customer_id', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <!-- Subject -->
                            <div class="mb-3">
                                <label for="subject" class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control" required placeholder="Enter message subject" value="<?php echo set_value('subject'); ?>">
                                <?php echo form_error('subject', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <!-- Message -->
                            <div class="mb-4">
                                <label for="message" class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" class="form-control" rows="6" required placeholder="Enter your message here..."><?php echo set_value('message'); ?></textarea>
                                <?php echo form_error('message', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                                <a href="<?php echo base_url('staff/admin_messages'); ?>" class="btn btn-outline-secondary">
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
                            <i class="fas fa-info-circle me-2"></i>Message Guidelines
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Best Practices:</h6>
                            <ul class="list-unstyled small">
                                <li><i class="fas fa-check text-success me-1"></i> Be clear and concise</li>
                                <li><i class="fas fa-check text-success me-1"></i> Use professional language</li>
                                <li><i class="fas fa-check text-success me-1"></i> Include relevant details</li>
                                <li><i class="fas fa-check text-success me-1"></i> Respond promptly to customer inquiries</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Message Types:</h6>
                            <ul class="list-unstyled small">
                                <li><strong>General Support</strong> - Answering questions and providing assistance</li>
                                <li><strong>Order Updates</strong> - Informing about order status changes</li>
                                <li><strong>Payment Issues</strong> - Addressing payment-related concerns</li>
                                <li><strong>Product Information</strong> - Providing product details and recommendations</li>
                            </ul>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tip:</strong> The customer will receive a notification when you send this message.
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

// Customer selection change handler
document.getElementById('customer_id').addEventListener('change', function() {
    const selectedCustomerId = this.value;
    if (selectedCustomerId) {
        // You can add AJAX call here to load customer details if needed
        console.log('Selected customer ID:', selectedCustomerId);
    }
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 