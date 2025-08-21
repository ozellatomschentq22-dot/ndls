<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-key me-2"></i>Change Password
                    </h1>
                    <a href="<?php echo base_url('customer/profile'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Profile
                    </a>
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

                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-lock me-2"></i>Update Your Password
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php echo form_open('customer/change_password'); ?>
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label fw-bold">Current Password <span class="text-danger">*</span></label>
                                        <input type="password" name="current_password" id="current_password" class="form-control" required 
                                               placeholder="Enter your current password">
                                        <?php echo form_error('current_password', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="new_password" class="form-label fw-bold">New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="new_password" id="new_password" class="form-control" required 
                                               placeholder="Enter your new password (minimum 6 characters)">
                                        <?php echo form_error('new_password', '<small class="text-danger">', '</small>'); ?>
                                        <small class="text-muted">Password must be at least 6 characters long.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label fw-bold">Confirm New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required 
                                               placeholder="Confirm your new password">
                                        <?php echo form_error('confirm_password', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Change Password
                                        </button>
                                        <a href="<?php echo base_url('customer/profile'); ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Password Requirements
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li>Minimum 6 characters long</li>
                                    <li>Use a combination of letters, numbers, and symbols for better security</li>
                                    <li>Don't use easily guessable information like your name or birthdate</li>
                                    <li>Consider using a password manager for better security</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

<?php $this->load->view('customer/common/footer'); ?> 