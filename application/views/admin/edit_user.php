<?php $this->load->view('admin/includes/header'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            <?php $this->load->view('admin/includes/sidebar'); ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">Edit User</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/users'); ?>">Users</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-outline-secondary btn-action">
                            <i class="fas fa-arrow-left me-2"></i>Back to Users
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

                <!-- Edit User Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">
                                    <i class="fas fa-user-edit me-2 text-primary"></i>
                                    Edit User Information
                                </h4>

                                <?php echo form_open('admin/edit_user/' . $user->id); ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="first_name" class="form-label">First Name *</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="first_name" 
                                                       name="first_name" 
                                                       value="<?php echo set_value('first_name', $user->first_name); ?>" 
                                                       required>
                                                <?php echo form_error('first_name', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="last_name" class="form-label">Last Name *</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="last_name" 
                                                       name="last_name" 
                                                       value="<?php echo set_value('last_name', $user->last_name); ?>" 
                                                       required>
                                                <?php echo form_error('last_name', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" 
                                               class="form-control" 
                                               id="email" 
                                               name="email" 
                                               value="<?php echo set_value('email', $user->email); ?>" 
                                               required>
                                        <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" 
                                               class="form-control" 
                                               id="phone" 
                                               name="phone" 
                                               value="<?php echo set_value('phone', $user->phone); ?>">
                                        <?php echo form_error('phone', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Role *</label>
                                                <select class="form-select" id="role" name="role" required>
                                                    <option value="customer" <?php echo set_select('role', 'customer', $user->role === 'customer'); ?>>Customer</option>
                                                    <option value="staff" <?php echo set_select('role', 'staff', $user->role === 'staff'); ?>>Staff</option>
                                                    <option value="admin" <?php echo set_select('role', 'admin', $user->role === 'admin'); ?>>Admin</option>
                                                </select>
                                                <?php echo form_error('role', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status *</label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="active" <?php echo set_select('status', 'active', $user->status === 'active'); ?>>Active</option>
                                                    <option value="inactive" <?php echo set_select('status', 'inactive', $user->status === 'inactive'); ?>>Inactive</option>
                                                </select>
                                                <?php echo form_error('status', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary btn-action">
                                            <i class="fas fa-save me-2"></i>Update User
                                        </button>
                                        <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-outline-secondary btn-action">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- User Information Sidebar -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    User Information
                                </h5>
                                
                                <div class="user-info">
                                    <div class="mb-3">
                                        <small class="text-muted d-block">User ID</small>
                                        <strong>#<?php echo $user->id; ?></strong>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Full Name</small>
                                        <strong><?php echo $user->first_name . ' ' . $user->last_name; ?></strong>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Email</small>
                                        <strong><?php echo $user->email; ?></strong>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Phone</small>
                                        <strong><?php echo isset($user->phone) && $user->phone ? $user->phone : 'Not provided'; ?></strong>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Role</small>
                                        <span class="badge bg-<?php echo $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : 'primary'); ?>">
                                            <?php echo ucfirst($user->role); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Status</small>
                                        <span class="badge bg-<?php echo $user->status === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($user->status); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Member Since</small>
                                        <strong><?php echo isset($user->created_at) ? date('M d, Y', strtotime($user->created_at)) : 'Unknown'; ?></strong>
                                    </div>
                                    
                                    <?php if (isset($user->updated_at) && $user->updated_at != $user->created_at): ?>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Last Updated</small>
                                            <strong><?php echo date('M d, Y H:i', strtotime($user->updated_at)); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <hr>
                                
                                <div class="d-grid gap-2">
                                    <a href="<?php echo base_url('admin/credit_wallet/' . $user->id); ?>" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-plus-circle me-2"></i>Credit Wallet
                                    </a>
                                    <a href="<?php echo base_url('admin/debit_wallet/' . $user->id); ?>" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-minus-circle me-2"></i>Debit Wallet
                                    </a>
                                    <a href="<?php echo base_url('admin/wallet_transactions/' . $user->id); ?>" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-history me-2"></i>View Transactions
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 