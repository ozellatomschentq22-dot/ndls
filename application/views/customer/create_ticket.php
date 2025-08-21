<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-plus me-2"></i>Create Support Ticket
                    </h1>
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
                                <?php echo form_open('customer/create_ticket'); ?>
                                    
                                    <!-- Subject -->
                                    <div class="mb-3">
                                        <label for="subject" class="form-label fw-bold">Subject <span class="text-danger">*</span></label>
                                        <input type="text" name="subject" id="subject" class="form-control" required placeholder="Brief description of your issue">
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
                                        <label for="message" class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                                        <textarea name="message" id="message" class="form-control" rows="6" required placeholder="Please provide detailed information about your issue..."><?php echo set_value('message'); ?></textarea>
                                        <?php echo form_error('message', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Ticket
                                        </button>
                                        <a href="<?php echo base_url('customer/support_tickets'); ?>" class="btn btn-outline-secondary">
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
                                    <h6>Before creating a ticket:</h6>
                                    <ul class="small text-muted">
                                        <li>Check our FAQ section first</li>
                                        <li>Provide clear, detailed information</li>
                                        <li>Include relevant order numbers if applicable</li>
                                        <li>Be specific about the issue you're facing</li>
                                    </ul>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Response Time:</h6>
                                    <ul class="small text-muted">
                                        <li><strong>Urgent:</strong> Within 2-4 hours</li>
                                        <li><strong>High:</strong> Within 24 hours</li>
                                        <li><strong>Medium:</strong> Within 48 hours</li>
                                        <li><strong>Low:</strong> Within 72 hours</li>
                                    </ul>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="<?php echo base_url('customer/support_tickets'); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-list me-2"></i>View My Tickets
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php $this->load->view('customer/common/footer'); ?> 