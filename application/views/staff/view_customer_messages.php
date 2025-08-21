<?php $this->load->view('staff/includes/header'); ?>

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
    
    .chat-container {
        height: 700px;
        display: flex;
        flex-direction: column;
        max-height: 700px;
        min-height: 700px;
        overflow: hidden;
        position: relative;
        background: #f0f2f5;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .chat-header {
        background: #198754;
        color: white;
        padding: 1rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    
    .messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        background: #e5ddd5;
        height: 520px;
        max-height: 520px;
        min-height: 520px;
    }
    
    .message-wrapper {
        display: flex;
        margin-bottom: 0.75rem;
    }
    
    .message-outgoing {
        justify-content: flex-end;
    }
    
    .message-incoming {
        justify-content: flex-start;
    }
    
    .message-bubble {
        max-width: 65%;
        position: relative;
    }
    
    .message-outgoing .message-bubble {
        background: #dcf8c6;
        border-radius: 18px 18px 4px 18px;
        padding: 0.75rem 1rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .message-incoming .message-bubble {
        background: white;
        border-radius: 18px 18px 18px 4px;
        padding: 0.75rem 1rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .message-content {
        font-size: 0.95rem;
        line-height: 1.4;
        word-wrap: break-word;
        margin-bottom: 0.25rem;
        color: #303030;
    }
    
    .message-time {
        font-size: 0.75rem;
        color: #667781;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.25rem;
        margin-top: 0.25rem;
    }
    
    .message-sender {
        font-weight: 600;
        color: #198754;
        margin-right: auto;
        font-size: 0.7rem;
    }
    
    .reply-input {
        background: #f0f2f5;
        border-top: 1px solid #e9ecef;
        padding: 1rem;
        height: 80px;
        max-height: 80px;
        min-height: 80px;
        flex-shrink: 0;
        border-radius: 0 0 12px 12px;
    }
    
    .reply-input .input-group {
        background: white;
        border-radius: 25px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .reply-input textarea {
        border: none;
        resize: none;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        line-height: 1.4;
        background: transparent;
    }
    
    .reply-input textarea:focus {
        outline: none;
        box-shadow: none;
    }
    
    .reply-input .btn {
        border-radius: 50%;
        width: 40px;
        height: 40px;
        margin: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #198754;
        border: none;
        color: white;
        transition: all 0.2s ease;
    }
    
    .reply-input .btn:hover {
        background: #20c997;
        transform: scale(1.05);
    }
    
    .customer-info {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        color: white;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .customer-info .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: 1rem;
    }
    
    .customer-info .card-body {
        padding: 1rem;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .info-item:last-child {
        margin-bottom: 0;
    }
    
    .info-item i {
        width: 16px;
        color: rgba(255, 255, 255, 0.8);
    }
    
    @media (max-width: 768px) {
        .main-content {
            width: 100% !important;
            margin-left: 0 !important;
        }
        
        .chat-container {
            height: 500px;
        }
        
        .message-bubble {
            max-width: 85%;
        }
    }
</style>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-comments me-2 text-success"></i>Customer Chat
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('staff/admin_messages'); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Messages
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
            <div class="col-lg-8">
                <div class="chat-container">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0"><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></h5>
                                <small>Customer</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Messages Area -->
                    <div class="messages-area" id="messages-area">
                        <?php if (empty($messages)): ?>
                            <div class="text-center text-muted mt-4">
                                <i class="fas fa-comments fa-3x mb-3 opacity-50"></i>
                                <h5>No messages yet</h5>
                                <p>Start a conversation with this customer</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($messages as $message): ?>
                                <div class="message-wrapper <?php echo isset($message->is_admin_reply) && $message->is_admin_reply ? 'message-outgoing' : 'message-incoming'; ?>">
                                    <div class="message-bubble">
                                        <div class="message-content">
                                            <?php echo htmlspecialchars($message->message); ?>
                                        </div>
                                        <div class="message-time">
                                            <?php echo date('g:i A', strtotime($message->created_at)); ?>
                                            <?php if (isset($message->is_admin_reply) && $message->is_admin_reply): ?>
                                                <span class="message-sender">You</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Reply Input Area -->
                    <div class="reply-input">
                        <form id="reply-form">
                            <div class="input-group">
                                <textarea id="reply-message" class="form-control" placeholder="Type a message..." rows="1" maxlength="1000"></textarea>
                                <button type="submit" class="btn" id="send-reply-btn">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card customer-info">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <span><strong>Name:</strong> <?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span><strong>Email:</strong> <?php echo htmlspecialchars($customer->email); ?></span>
                        </div>
                        <?php if (!empty($customer->phone)): ?>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span><strong>Phone:</strong> <?php echo htmlspecialchars($customer->phone); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Joined:</strong> <?php echo date('M j, Y', strtotime($customer->created_at)); ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-comments"></i>
                            <span><strong>Messages:</strong> <?php echo count($messages); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tools me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo base_url('staff/view_customer/' . $customer->id); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-2"></i>View Customer Details
                            </a>
                            <a href="<?php echo base_url('staff/orders'); ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-shopping-bag me-2"></i>View Orders
                            </a>
                            <a href="<?php echo base_url('staff/tickets'); ?>" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-ticket-alt me-2"></i>Support Tickets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesArea = document.getElementById('messages-area');
    const replyForm = document.getElementById('reply-form');
    const replyMessage = document.getElementById('reply-message');
    
    // Scroll to bottom
    if (messagesArea) {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }
    
    // Auto-resize textarea
    if (replyMessage) {
        replyMessage.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }
    
    // Handle form submission
    if (replyForm) {
        replyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendReply();
        });
        
        // Handle Enter key
        replyMessage.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendReply();
            }
        });
    }
});

function sendReply() {
    const replyMessage = document.getElementById('reply-message');
    const sendReplyBtn = document.getElementById('send-reply-btn');
    const message = replyMessage.value.trim();
    
    if (!message) {
        alert('Please enter a reply message');
        return;
    }
    
    // Disable form
    replyMessage.disabled = true;
    sendReplyBtn.disabled = true;
    sendReplyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    // Create form data
    const formData = new FormData();
    formData.append('customer_id', '<?php echo $customer->id; ?>');
    formData.append('message', message);
    
    // Send reply via AJAX
    fetch('<?php echo base_url('staff/send_staff_reply'); ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showAlert('Reply sent successfully!', 'success');
            replyMessage.value = '';
            replyMessage.style.height = 'auto';
            
            // Reload page to show new message
            location.reload();
        } else {
            showAlert('Error: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Failed to send reply. Please try again.', 'danger');
    })
    .finally(() => {
        // Re-enable form
        replyMessage.disabled = false;
        sendReplyBtn.disabled = false;
        sendReplyBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        replyMessage.focus();
    });
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert alert after the page header
    const header = document.querySelector('.d-flex.justify-content-between');
    header.parentNode.insertBefore(alertDiv, header.nextSibling);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>

<?php $this->load->view('staff/includes/footer'); ?> 