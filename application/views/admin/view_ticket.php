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
    
    .reply-item {
        margin-bottom: 1rem;
        max-width: 80%;
    }
    
    .reply-item.admin-reply {
        margin-left: auto;
        background-color: #007bff;
        color: white;
        border-radius: 15px 15px 0 15px;
        padding: 12px 16px;
    }
    
    .reply-item.customer-reply {
        margin-right: auto;
        background-color: #e9ecef;
        color: #212529;
        border-radius: 15px 15px 15px 0;
        padding: 12px 16px;
    }
    
    .reply-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 0.875rem;
    }
    
    .reply-header.admin-reply {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .reply-header.customer-reply {
        color: #6c757d;
    }
    
    .reply-message {
        line-height: 1.4;
    }
    
    .reply-message.admin-reply {
        color: white;
    }
    
    .reply-message.customer-reply {
        color: #212529;
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-ticket-alt me-2"></i>View Support Ticket
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/tickets'); ?>" class="btn btn-sm btn-outline-secondary">
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

        <!-- Ticket Details and Replies -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Ticket Replies -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-comments me-2"></i>Conversation
                            <?php if (isset($replies) && count($replies) > 0): ?>
                                <span class="badge bg-secondary ms-2"><?php echo count($replies); ?> replies</span>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($replies) && !empty($replies)): ?>
                            <?php foreach ($replies as $reply): ?>
                                <?php $isAdmin = $reply->role === 'admin' || $reply->role === 'staff'; ?>
                                <div class="reply-item <?php echo $isAdmin ? 'admin-reply' : 'customer-reply'; ?>">
                                    <div class="reply-header <?php echo $isAdmin ? 'admin-reply' : 'customer-reply'; ?>">
                                        <div>
                                            <strong><?php echo $reply->first_name . ' ' . $reply->last_name; ?></strong>
                                            <span class="badge <?php echo $isAdmin ? 'bg-light text-primary' : 'bg-secondary'; ?> ms-2">
                                                <?php echo ucfirst($reply->role); ?>
                                            </span>
                                        </div>
                                        <small><?php echo date('M j, Y H:i', strtotime($reply->created_at)); ?></small>
                                    </div>
                                    <div class="reply-message <?php echo $isAdmin ? 'admin-reply' : 'customer-reply'; ?>">
                                        <?php echo nl2br(htmlspecialchars($reply->message)); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-comments fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No replies yet. Be the first to respond!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Reply Form -->
                <?php if ($ticket->status !== 'closed'): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-reply me-2"></i>Add Reply
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('admin/add_ticket_reply/' . $ticket->id); ?>
                            <div class="mb-3">
                                <label for="message" class="form-label">Your Reply *</label>
                                <textarea class="form-control" 
                                          id="message" 
                                          name="message" 
                                          rows="4" 
                                          required 
                                          placeholder="Type your reply here..."><?php echo set_value('message'); ?></textarea>
                                <?php echo form_error('message', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>Send Reply
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-lock fa-2x text-muted mb-3"></i>
                        <p class="text-muted">This ticket is closed. No new replies can be added.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <!-- Ticket Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-ticket-alt me-2 text-primary"></i>Ticket Details
                            </h5>
                            <div class="d-flex gap-2">
                                <span class="badge bg-<?php echo $ticket->status === 'open' ? 'warning' : ($ticket->status === 'in_progress' ? 'info' : 'success'); ?> fs-6">
                                    <?php echo ucwords(str_replace('_', ' ', $ticket->status)); ?>
                                </span>
                                <span class="badge bg-<?php echo $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'success'); ?> fs-6">
                                    <?php echo ucfirst($ticket->priority); ?> Priority
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ticket Number</label>
                            <p class="mb-0">#<?php echo $ticket->ticket_number; ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <p class="mb-0">
                                <span class="badge bg-secondary">
                                    <?php echo ucfirst($ticket->category); ?>
                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Subject</label>
                            <p class="mb-0 fs-5"><?php echo htmlspecialchars($ticket->subject); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Message</label>
                            <div class="border rounded p-3 bg-light">
                                <?php echo nl2br(htmlspecialchars($ticket->message)); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Created Date</label>
                            <p class="mb-0"><?php echo date('M j, Y H:i', strtotime($ticket->created_at)); ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Last Updated</label>
                            <p class="mb-0"><?php echo date('M j, Y H:i', strtotime($ticket->updated_at)); ?></p>
                        </div>

                        <?php if ($ticket->status !== 'closed'): ?>
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3">Update Status</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php if ($ticket->status === 'open'): ?>
                                    <a href="<?php echo base_url('admin/update_ticket_status/' . $ticket->id . '/in_progress'); ?>" 
                                       class="btn btn-info btn-sm" 
                                       onclick="return confirm('Mark ticket as in progress?')">
                                        <i class="fas fa-clock me-1"></i>Mark In Progress
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($ticket->status === 'in_progress'): ?>
                                    <a href="<?php echo base_url('admin/update_ticket_status/' . $ticket->id . '/closed'); ?>" 
                                       class="btn btn-success btn-sm" 
                                       onclick="return confirm('Close this ticket?')">
                                        <i class="fas fa-check me-1"></i>Close Ticket
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($ticket->status !== 'closed'): ?>
                                    <a href="<?php echo base_url('admin/update_ticket_status/' . $ticket->id . '/closed'); ?>" 
                                       class="btn btn-secondary btn-sm" 
                                       onclick="return confirm('Close this ticket?')">
                                        <i class="fas fa-times me-1"></i>Close Ticket
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Customer Name</label>
                            <p class="mb-0"><?php echo $customer->first_name . ' ' . $customer->last_name; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="mb-0"><?php echo $customer->email; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phone</label>
                            <p class="mb-0"><?php echo $customer->phone ?: 'Not provided'; ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Member Since</label>
                            <p class="mb-0"><?php echo date('M j, Y', strtotime($customer->created_at)); ?></p>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo base_url('admin/view_customer/' . $customer->id); ?>" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>View Customer Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 