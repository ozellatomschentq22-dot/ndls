<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-ticket-alt me-2"></i>View Ticket #<?php echo $ticket->id; ?>
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('staff/tickets'); ?>" class="btn btn-outline-secondary btn-sm">
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
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Ticket Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Subject:</strong> <?php echo $ticket->subject; ?></p>
                                <p><strong>Customer:</strong> <?php echo $ticket->customer_name; ?></p>
                                <p><strong>Category:</strong> <?php echo ucfirst($ticket->category); ?></p>
                                <p><strong>Priority:</strong> 
                                    <span class="badge bg-<?php echo $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'success'); ?>">
                                        <?php echo ucfirst($ticket->priority); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-<?php echo $ticket->status === 'open' ? 'primary' : ($ticket->status === 'in_progress' ? 'warning' : 'success'); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $ticket->status)); ?>
                                    </span>
                                </p>
                                <p><strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($ticket->created_at)); ?></p>
                                <p><strong>Last Updated:</strong> <?php echo date('M d, Y H:i', strtotime($ticket->updated_at)); ?></p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h6>Message:</h6>
                            <div class="border rounded p-3 bg-light">
                                <?php echo nl2br(htmlspecialchars($ticket->message)); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Replies -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-comments me-2"></i>Replies
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($replies)): ?>
                            <?php foreach ($replies as $reply): ?>
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong><?php echo $reply->replied_by; ?></strong>
                                            <small class="text-muted ms-2"><?php echo date('M d, Y H:i', strtotime($reply->created_at)); ?></small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <?php echo nl2br(htmlspecialchars($reply->reply)); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No replies yet.</p>
                        <?php endif; ?>

                        <!-- Add Reply Form -->
                        <div class="mt-4">
                            <h6>Add Reply:</h6>
                            <?php echo form_open('staff/add_ticket_reply'); ?>
                                <input type="hidden" name="ticket_id" value="<?php echo $ticket->id; ?>">
                                <div class="mb-3">
                                    <textarea class="form-control" name="reply" rows="4" placeholder="Type your reply..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-reply me-2"></i>Send Reply
                                </button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Actions -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs me-2"></i>Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Update Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status</label>
                            <select class="form-select" id="status">
                                <option value="open" <?php echo $ticket->status === 'open' ? 'selected' : ''; ?>>Open</option>
                                <option value="in_progress" <?php echo $ticket->status === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="closed" <?php echo $ticket->status === 'closed' ? 'selected' : ''; ?>>Closed</option>
                            </select>
                        </div>
                        
                        <button type="button" class="btn btn-warning btn-sm mb-2" onclick="updateStatus()">
                            <i class="fas fa-save me-2"></i>Update Status
                        </button>
                        
                        <hr>
                        
                        <!-- Quick Actions -->
                        <div class="d-grid gap-2">
                            <a href="<?php echo base_url('staff/view_customer/' . $ticket->user_id); ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-user me-2"></i>View Customer
                            </a>
                            <a href="mailto:<?php echo $ticket->customer_email; ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-2"></i>Email Customer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus() {
    const status = document.getElementById('status').value;
    const ticketId = <?php echo $ticket->id; ?>;
    
    fetch('<?php echo base_url('staff/update_ticket_status'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `ticket_id=${ticketId}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status updated successfully!');
            location.reload();
        } else {
            alert('Failed to update status: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status');
    });
}
</script>

<?php $this->load->view('staff/includes/footer'); ?> 