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
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-user-plus me-2"></i>Add New Customer
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/customers'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Customers
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
                        <h5 class="mb-0">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('staff/add_customer', ['id' => 'addCustomerForm']); ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo set_value('first_name'); ?>" required>
                                    <?php echo form_error('first_name', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo set_value('last_name'); ?>" required>
                                    <?php echo form_error('last_name', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username *</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?php echo set_value('username'); ?>" required>
                                    <small class="text-muted">Username must be unique and will be used for login</small>
                                    <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo set_value('email'); ?>" required>
                                    <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo set_value('phone'); ?>">
                                    <?php echo form_error('phone', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Account Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" <?php echo set_select('status', 'active', TRUE); ?>>Active</option>
                                        <option value="inactive" <?php echo set_select('status', 'inactive'); ?>>Inactive</option>
                                    </select>
                                    <?php echo form_error('status', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <small class="text-muted">Minimum 6 characters</small>
                                    <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <?php echo form_error('confirm_password', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo base_url('staff/customers'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Customer
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Info</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-success">Customer Account</h6>
                            <small class="text-muted">
                                Customers can place orders, manage their profile, view order history, and access customer support.
                            </small>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-info">Required Fields</h6>
                            <small class="text-muted">
                                • First Name and Last Name<br>
                                • Username (unique, auto-generated)<br>
                                • Email Address (unique)<br>
                                • Password (min 6 characters)
                            </small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Username will be auto-generated from first and last name if left empty.
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> Make sure to provide a strong password and verify the email address is correct.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('addCustomerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    
    // Username validation
    if (username.length < 3) {
        e.preventDefault();
        alert('Username must be at least 3 characters long!');
        return false;
    }
    
    if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        e.preventDefault();
        alert('Username can only contain letters, numbers, and underscores!');
        return false;
    }
    
    // Email validation
    if (!email.includes('@') || !email.includes('.')) {
        e.preventDefault();
        alert('Please enter a valid email address!');
        return false;
    }
    
    // Password validation
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long!');
        return false;
    }
});

// Auto-format phone number
document.getElementById('phone').addEventListener('blur', function() {
    let phone = this.value.replace(/\D/g, '');
    if (phone.length === 10) {
        phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    } else if (phone.length === 11 && phone.startsWith('1')) {
        phone = phone.substring(1).replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    }
    this.value = phone;
});

// Auto-generate username from first and last name
document.getElementById('first_name').addEventListener('blur', function() {
    const firstName = this.value.toLowerCase().replace(/[^a-z]/g, '');
    const lastName = document.getElementById('last_name').value.toLowerCase().replace(/[^a-z]/g, '');
    const usernameField = document.getElementById('username');
    
    if (firstName && lastName && !usernameField.value) {
        usernameField.value = firstName + '_' + lastName;
    }
});

document.getElementById('last_name').addEventListener('blur', function() {
    const firstName = document.getElementById('first_name').value.toLowerCase().replace(/[^a-z]/g, '');
    const lastName = this.value.toLowerCase().replace(/[^a-z]/g, '');
    const usernameField = document.getElementById('username');
    
    if (firstName && lastName && !usernameField.value) {
        usernameField.value = firstName + '_' + lastName;
    }
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 