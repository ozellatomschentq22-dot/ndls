<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-comments me-2 text-primary"></i>All Chats
                </h1>
                <p class="text-muted mb-0">Manage customer conversations and provide support.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/start_new_message'); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Start New Message
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages List -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($messages)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Total Messages</th>
                                    <th>Last Message</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $message): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    <?php 
                                                    $firstName = $message->first_name ?? 'C';
                                                    $lastName = $message->last_name ?? 'U';
                                                    echo strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1)); 
                                                    ?>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold">
                                                    <?php 
                                                    if ($message->first_name && $message->last_name) {
                                                        echo htmlspecialchars($message->first_name . ' ' . $message->last_name);
                                                    } else {
                                                        echo htmlspecialchars($message->email ?? 'Unknown Customer');
                                                    }
                                                    ?>
                                                </div>
                                                <small class="text-muted"><?php echo htmlspecialchars($message->email ?? 'No email'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo $message->message_count; ?> messages</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('M j, Y g:i A', strtotime($message->last_message_time)); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('staff/view_customer_messages/' . ($message->customer_id ?? 0)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-comments me-1"></i>View Chat
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No conversations found</h5>
                        <p class="text-muted">There are no customer conversations yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('staff/includes/footer'); ?> 