<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-user me-2"></i>My Profile
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?php echo base_url('customer/change_password'); ?>" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Change Password
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

                <!-- Phone Number Missing Warning -->
                <?php if (empty($user->phone)): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone fa-2x me-3 text-warning"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-2">Phone Number Required</h5>
                                <p class="mb-2">A phone number is required for order notifications and customer support. Please add your phone number below.</p>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addPhoneModal">
                                    <i class="fas fa-plus me-2"></i>Add Phone Number
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Profile Information -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-edit me-2"></i>Personal Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- View Mode -->
                                <div id="viewMode">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">First Name</label>
                                            <p class="form-control-plaintext"><?php echo htmlspecialchars($user->first_name); ?></p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold text-muted">Last Name</label>
                                            <p class="form-control-plaintext"><?php echo htmlspecialchars($user->last_name); ?></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Email Address</label>
                                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user->email); ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Phone Number</label>
                                        <p class="form-control-plaintext"><?php echo htmlspecialchars($user->phone ?: 'Not provided'); ?></p>
                                    </div>
                                </div>

                                <!-- Edit Mode -->
                                <div id="editMode" style="display: none;">
                                    <?php echo form_open('customer/profile'); ?>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="first_name" class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                                <input type="text" name="first_name" id="first_name" class="form-control" required 
                                                       value="<?php echo set_value('first_name', $user->first_name); ?>">
                                                <?php echo form_error('first_name', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="last_name" class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" name="last_name" id="last_name" class="form-control" required 
                                                       value="<?php echo set_value('last_name', $user->last_name); ?>">
                                                <?php echo form_error('last_name', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control" required 
                                                   value="<?php echo set_value('email', $user->email); ?>">
                                            <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" name="phone" id="phone" class="form-control" 
                                                   value="<?php echo set_value('phone', $user->phone); ?>" 
                                                   placeholder="Enter phone number">
                                            <?php echo form_error('phone', '<small class="text-danger">', '</small>'); ?>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Profile
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="cancelEditBtn">
                                                <i class="fas fa-times me-2"></i>Cancel
                                            </button>
                                        </div>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Account Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Account Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Member Since:</strong><br>
                                    <span class="text-muted"><?php echo isset($user->created_at) ? date('M j, Y', strtotime($user->created_at)) : 'N/A'; ?></span>
                                </div>
                                <div class="mb-3">
                                    <strong>Account Status:</strong><br>
                                    <span class="badge bg-success">Active</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Last Login:</strong><br>
                                    <span class="text-muted"><?php echo isset($user->last_login) ? date('M j, Y H:i', strtotime($user->last_login)) : 'N/A'; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-bolt me-2"></i>Quick Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="<?php echo base_url('customer/orders'); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-shopping-bag me-2"></i>View Orders
                                    </a>
                                    <a href="<?php echo base_url('customer/wallet'); ?>" class="btn btn-outline-success">
                                        <i class="fas fa-wallet me-2"></i>My Wallet
                                    </a>
                                    <a href="<?php echo base_url('customer/addresses'); ?>" class="btn btn-outline-info">
                                        <i class="fas fa-map-marker-alt me-2"></i>My Addresses
                                    </a>
                                    <a href="<?php echo base_url('customer/support_tickets'); ?>" class="btn btn-outline-warning">
                                        <i class="fas fa-ticket-alt me-2"></i>Support Tickets
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewModeBtn = document.createElement('button');
    viewModeBtn.type = 'button';
    viewModeBtn.className = 'btn btn-outline-primary';
    viewModeBtn.innerHTML = '<i class="fas fa-eye me-2"></i>View Mode';
    viewModeBtn.id = 'viewModeBtn';
    
    const editModeBtn = document.createElement('button');
    editModeBtn.type = 'button';
    editModeBtn.className = 'btn btn-primary';
    editModeBtn.innerHTML = '<i class="fas fa-edit me-2"></i>Edit Mode';
    editModeBtn.id = 'editModeBtn';
    
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    
    // Add buttons to the header
    const header = document.querySelector('.d-flex.justify-content-between');
    const btnGroup = document.createElement('div');
    btnGroup.className = 'btn-group';
    btnGroup.appendChild(viewModeBtn);
    btnGroup.appendChild(editModeBtn);
    header.appendChild(btnGroup);
    
    // Show view mode by default
    function showViewMode() {
        viewMode.style.display = 'block';
        editMode.style.display = 'none';
        viewModeBtn.classList.remove('btn-primary');
        viewModeBtn.classList.add('btn-outline-primary');
        editModeBtn.classList.remove('btn-outline-primary');
        editModeBtn.classList.add('btn-primary');
    }

    // Show edit mode
    function showEditMode() {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
        viewModeBtn.classList.remove('btn-outline-primary');
        viewModeBtn.classList.add('btn-primary');
        editModeBtn.classList.remove('btn-primary');
        editModeBtn.classList.add('btn-outline-primary');
    }

    // Make showEditMode globally accessible
    window.showEditMode = showEditMode;

    // Event listeners
    viewModeBtn.addEventListener('click', showViewMode);
    editModeBtn.addEventListener('click', showEditMode);
    cancelEditBtn.addEventListener('click', showViewMode);

    // Show view mode by default
    showViewMode();
});
</script>

<!-- Add Phone Number Modal -->
<div class="modal fade" id="addPhoneModal" tabindex="-1" aria-labelledby="addPhoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPhoneModalLabel">
                    <i class="fas fa-phone me-2"></i>Add Phone Number
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPhoneForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your phone number will be used for order notifications and customer support.
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone_number" name="phone" required 
                               placeholder="Enter your phone number (e.g., +1-555-123-4567)">
                        <div class="form-text">Include country code if applicable</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Phone Number
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Handle phone number form submission
document.getElementById('addPhoneForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const phoneNumber = formData.get('phone');
    
    if (!phoneNumber.trim()) {
        alert('Please enter a phone number');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;
    
    // Send AJAX request
    fetch('<?php echo base_url("customer/update_phone"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            'phone': phoneNumber
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            // Insert at the top of the page
            const container = document.querySelector('.container-fluid');
            container.insertBefore(alertDiv, container.firstChild);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addPhoneModal'));
            modal.hide();
            
            // Reload page after a short delay to update the display
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            alert(data.message || 'An error occurred. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>

<?php $this->load->view('customer/common/footer'); ?> 