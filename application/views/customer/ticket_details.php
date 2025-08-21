<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-ticket-alt me-2"></i>Ticket Details
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?php echo base_url('customer/support_tickets'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Tickets
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

                <div class="row">
                    <!-- Ticket Details -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Ticket Information
                                    </h5>
                                    <span class="badge bg-<?php echo $ticket->status === 'open' ? 'success' : ($ticket->status === 'in_progress' ? 'warning' : 'secondary'); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $ticket->status)); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Ticket Number:</strong><br>
                                        <span class="text-muted"><?php echo $ticket->ticket_number; ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Created:</strong><br>
                                        <span class="text-muted"><?php echo date('M j, Y H:i', strtotime($ticket->created_at)); ?></span>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Category:</strong><br>
                                        <span class="badge bg-info"><?php echo ucfirst($ticket->category); ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Priority:</strong><br>
                                        <span class="badge bg-<?php echo $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : ($ticket->priority === 'medium' ? 'info' : 'secondary')); ?>">
                                            <?php echo ucfirst($ticket->priority); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Subject:</strong><br>
                                    <h6 class="mt-1"><?php echo htmlspecialchars($ticket->subject); ?></h6>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Message:</strong><br>
                                    <div class="mt-2 p-3 bg-light rounded">
                                        <?php echo nl2br(htmlspecialchars($ticket->message)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Replies Section -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-comments me-2"></i>Conversation
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($replies)): ?>
                                    <?php foreach ($replies as $reply): ?>
                                        <div class="mb-4">
                                            <div class="d-flex <?php echo $reply->is_admin ? 'justify-content-end' : 'justify-content-start'; ?>">
                                                <div class="card <?php echo $reply->is_admin ? 'bg-primary text-white' : 'bg-light'; ?>" style="max-width: 80%;">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <strong><?php echo $reply->is_admin ? 'Support Team' : $user['first_name'] . ' ' . $user['last_name']; ?></strong>
                                                            <small class="<?php echo $reply->is_admin ? 'text-white-50' : 'text-muted'; ?>">
                                                                <?php echo date('M j, Y H:i', strtotime($reply->created_at)); ?>
                                                            </small>
                                                        </div>
                                                        <div class="<?php echo $reply->is_admin ? 'text-white' : ''; ?>">
                                                            <?php echo nl2br(htmlspecialchars($reply->message)); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-comments fa-2x mb-2"></i>
                                        <p>No replies yet. Be the first to respond!</p>
                                    </div>
                                <?php endif; ?>

                                <!-- Reply Form -->
                                <?php if ($ticket->status !== 'closed'): ?>
                                    <div class="mt-4 pt-3 border-top">
                                        <h6>Add Reply</h6>
                                        <?php echo form_open('customer/reply_ticket/' . $ticket->id); ?>
                                            <div class="mb-3">
                                                <textarea name="message" class="form-control" rows="4" required placeholder="Type your reply here..."><?php echo set_value('message'); ?></textarea>
                                                <?php echo form_error('message', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-paper-plane me-2"></i>Send Reply
                                            </button>
                                        <?php echo form_close(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-secondary text-center">
                                        <i class="fas fa-lock me-2"></i>This ticket is closed. No more replies can be added.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-cog me-2"></i>Ticket Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <?php if ($ticket->status !== 'closed'): ?>
                                        <a href="<?php echo base_url('customer/close_ticket/' . $ticket->id); ?>" 
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to close this ticket?')">
                                            <i class="fas fa-times me-2"></i>Close Ticket
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo base_url('customer/reopen_ticket/' . $ticket->id); ?>" 
                                           class="btn btn-outline-success">
                                            <i class="fas fa-redo me-2"></i>Reopen Ticket
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo base_url('customer/support_tickets'); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-list me-2"></i>View All Tickets
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php $this->load->view('customer/common/footer'); ?> 