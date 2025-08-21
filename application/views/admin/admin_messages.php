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
    
    .customer-avatar {
        width: 40px;
        height: 40px;
        background: #007bff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }
    
    @media (max-width: 768px) {
        .main-content {
            width: 100% !important;
            margin-left: 0 !important;
        }
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-comments me-2"></i>Customer Messages
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button class="btn btn-outline-primary btn-sm" onclick="refreshMessages()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php 
                                    $unique_customers = array();
                                    foreach ($all_messages as $message) {
                                        $unique_customers[$message->customer_id] = true;
                                    }
                                    echo count($unique_customers);
                                ?></h4>
                                <small>Total Chats</small>
                            </div>
                            <div>
                                <i class="fas fa-comments fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo count($all_messages); ?></h4>
                                <small>Total Messages</small>
                            </div>
                            <div>
                                <i class="fas fa-envelope fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $unread_count; ?></h4>
                                <small>Unread Messages</small>
                            </div>
                            <div>
                                <i class="fas fa-exclamation-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-4" id="messageTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab">
                    <i class="fas fa-envelope me-2"></i>Unread Messages
                    <?php if ($unread_count > 0): ?>
                        <span class="badge bg-danger ms-2"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                    <i class="fas fa-list me-2"></i>All Chats
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="messageTabsContent">
            <!-- Unread Messages Tab -->
            <div class="tab-pane fade show active" id="unread" role="tabpanel">
                <?php 
                // Group unread messages by customer
                $unread_by_customer = array();
                foreach ($unread_messages as $message) {
                    $customer_id = $message->customer_id;
                    if (!isset($unread_by_customer[$customer_id])) {
                        $unread_by_customer[$customer_id] = array(
                            'customer_name' => $message->customer_name,
                            'messages' => array()
                        );
                    }
                    $unread_by_customer[$customer_id]['messages'][] = $message;
                }
                ?>
                
                <?php if (!empty($unread_by_customer)): ?>
                    <div class="row">
                        <?php foreach ($unread_by_customer as $customer_id => $customer_data): ?>
                            <div class="col-lg-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="customer-avatar me-3">
                                                    <?php echo strtoupper(substr($customer_data['customer_name'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($customer_data['customer_name']); ?></h6>
                                                    <small class="text-muted"><?php echo count($customer_data['messages']); ?> unread messages</small>
                                                </div>
                                            </div>
                                            <a href="<?php echo base_url('admin/view_customer_messages/' . $customer_id); ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Chat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5>No Unread Messages</h5>
                        <p class="text-muted">All customer messages have been read.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- All Chats Tab -->
            <div class="tab-pane fade" id="all" role="tabpanel">
                <?php 
                // Group all messages by customer
                $all_by_customer = array();
                foreach ($all_messages as $message) {
                    $customer_id = $message->customer_id;
                    if (!isset($all_by_customer[$customer_id])) {
                        $all_by_customer[$customer_id] = array(
                            'customer_name' => $message->customer_name,
                            'messages' => array()
                        );
                    }
                    $all_by_customer[$customer_id]['messages'][] = $message;
                }
                ?>
                
                <?php if (!empty($all_by_customer)): ?>
                    <div class="row">
                        <?php foreach ($all_by_customer as $customer_id => $customer_data): ?>
                            <div class="col-lg-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="customer-avatar me-3">
                                                    <?php echo strtoupper(substr($customer_data['customer_name'], 0, 1)); ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($customer_data['customer_name']); ?></h6>
                                                    <small class="text-muted"><?php echo count($customer_data['messages']); ?> total messages</small>
                                                </div>
                                            </div>
                                            <a href="<?php echo base_url('admin/view_customer_messages/' . $customer_id); ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Chat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <h5>No Messages</h5>
                        <p class="text-muted">No customer messages have been received yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function refreshMessages() {
    location.reload();
}

function markAsRead(messageId) {
    fetch('<?php echo base_url('admin/mark_message_read'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'message_id=' + messageId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to mark message as read');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark message as read');
    });
}

function deleteMessage(messageId) {
    if (confirm('Are you sure you want to delete this message?')) {
        fetch('<?php echo base_url('admin/delete_message'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'message_id=' + messageId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete message');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete message');
        });
    }
}

// Check for new messages every 30 seconds
setInterval(function() {
    fetch('<?php echo base_url('admin/get_unread_count'); ?>')
    .then(response => response.json())
    .then(data => {
        const notification = document.getElementById('admin-message-notification');
        if (data.unread_count > 0) {
            notification.textContent = data.unread_count;
            notification.style.display = 'inline';
        } else {
            notification.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error checking unread count:', error);
    });
}, 30000);
</script>

<?php $this->load->view('admin/includes/footer'); ?> 