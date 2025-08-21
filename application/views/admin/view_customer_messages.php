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
    
    .chat-container .card-body {
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 0;
        overflow: hidden;
    }
    
    .chat-header {
        background: #075e54;
        color: white;
        padding: 1rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    
    .chat-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .chat-avatar {
        width: 45px;
        height: 45px;
        background: #128c7e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
    }
    
    .chat-user-info h5 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .chat-user-info small {
        opacity: 0.8;
        font-size: 0.85rem;
    }
    
    .chat-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-indicator {
        width: 8px;
        height: 8px;
        background: #25d366;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        background: #e5ddd5;
        background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        height: 520px;
        max-height: 520px;
        min-height: 520px;
    }
    
    .message-wrapper {
        display: flex;
        margin-bottom: 0.75rem;
        animation: fadeIn 0.3s ease-in;
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
        position: relative;
    }
    
    .message-outgoing .message-bubble::before {
        content: '';
        position: absolute;
        right: -8px;
        top: 0;
        width: 0;
        height: 0;
        border: 8px solid transparent;
        border-left-color: #dcf8c6;
        border-top: none;
        border-right: none;
    }
    
    .message-incoming .message-bubble {
        background: white;
        border-radius: 18px 18px 18px 4px;
        padding: 0.75rem 1rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    
    .message-incoming .message-bubble::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 0;
        width: 0;
        height: 0;
        border: 8px solid transparent;
        border-right-color: white;
        border-top: none;
        border-left: none;
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
        color: #128c7e;
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
        background: #25d366;
        border: none;
        color: white;
        transition: all 0.2s ease;
    }
    
    .reply-input .btn:hover {
        background: #128c7e;
        transform: scale(1.05);
    }
    
    .reply-input .btn:active {
        transform: scale(0.95);
    }
    
    .customer-info {
        background: linear-gradient(135deg, #075e54 0%, #128c7e 100%);
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
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Scrollbar styling */
    .messages-area::-webkit-scrollbar {
        width: 6px;
    }
    
    .messages-area::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .messages-area::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .messages-area::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Auto-resize textarea */
    #reply-message {
        min-height: 40px;
        max-height: 120px;
        overflow-y: auto;
    }
    
    .quick-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    
    .quick-action-btn {
        background: #e9ecef;
        border: none;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        color: #495057;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .quick-action-btn:hover {
        background: #dee2e6;
        transform: translateY(-1px);
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
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-comments me-2"></i>Customer Chat
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('admin/admin_messages'); ?>" class="btn btn-outline-secondary">
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
                    <!-- WhatsApp-style Header -->
                    <div class="chat-header">
                        <div class="chat-header-left">
                            <div class="chat-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="chat-user-info">
                                <h5><?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?></h5>
                                <small>Customer</small>
                            </div>
                        </div>
                        <div class="chat-status">
                            <div class="status-indicator"></div>
                            <small>Online</small>
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
                                            <?php if ($message->is_read): ?>
                                                <i class="fas fa-check-double text-primary" title="Read"></i>
                                            <?php else: ?>
                                                <i class="fas fa-check text-muted" title="Sent"></i>
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
                        
                        <!-- Quick Action Buttons -->
                        <div class="quick-actions">
                            <button type="button" class="quick-action-btn" onclick="sendQuickMessage('Hello! How can I help you today?')">
                                <i class="fas fa-hand-wave me-1"></i>Hello
                            </button>
                            <button type="button" class="quick-action-btn" onclick="sendQuickMessage('Thank you for contacting us!')">
                                <i class="fas fa-heart me-1"></i>Thank you
                            </button>
                            <button type="button" class="quick-action-btn" onclick="sendQuickMessage('Your order is being processed.')">
                                <i class="fas fa-box me-1"></i>Order Status
                            </button>
                            <button type="button" class="quick-action-btn" onclick="sendQuickMessage('Is there anything else I can help you with?')">
                                <i class="fas fa-question me-1"></i>Anything else?
                            </button>
                        </div>
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
                            <a href="<?php echo base_url('admin/view_customer/' . $customer->id); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-2"></i>View Customer Details
                            </a>
                            <a href="<?php echo base_url('admin/orders?customer_id=' . $customer->id); ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-shopping-bag me-2"></i>View Orders
                            </a>
                            <a href="<?php echo base_url('admin/support_tickets?customer_id=' . $customer->id); ?>" class="btn btn-outline-warning btn-sm">
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
// Initialize chat
document.addEventListener('DOMContentLoaded', function() {
    const messagesArea = document.getElementById('messages-area');
    const replyForm = document.getElementById('reply-form');
    const replyMessage = document.getElementById('reply-message');
    const sendReplyBtn = document.getElementById('send-reply-btn');
    
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
        
        // Handle Enter key (send on Enter, new line on Shift+Enter)
        replyMessage.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendReply();
            }
        });
    }
    
    // Real-time message polling
    let lastMessageId = <?php echo !empty($messages) ? end($messages)->id : 0; ?>;
    setInterval(pollNewMessages, 3000); // Poll every 3 seconds
});

function pollNewMessages() {
    fetch('<?php echo base_url('admin/get_new_customer_messages'); ?>?customer_id=<?php echo $customer->id; ?>&last_id=' + lastMessageId)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.messages && data.messages.length > 0) {
            data.messages.forEach(message => {
                addIncomingMessage(message);
                lastMessageId = Math.max(lastMessageId, message.id);
            });
            
            // Update page title if there are new messages
            if (data.messages.length > 0) {
                document.title = '(New Message) Customer Chat - USA Pharmacy 365';
                
                // Show notification
                showAlert('New message received!', 'info');
                
                // Play notification sound (optional)
                playNotificationSound();
            }
        }
    })
    .catch(error => {
        console.error('Error polling messages:', error);
    });
}

function addIncomingMessage(message) {
    const messagesArea = document.getElementById('messages-area');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message-wrapper message-incoming';
    
    const timeString = new Date(message.created_at).toLocaleString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    
    messageDiv.innerHTML = `
        <div class="message-bubble">
            <div class="message-content">${escapeHtml(message.message)}</div>
            <div class="message-time">
                ${timeString}
                <i class="fas fa-check text-muted" title="Sent"></i>
            </div>
        </div>
    `;
    
    messagesArea.appendChild(messageDiv);
    messagesArea.scrollTop = messagesArea.scrollHeight;
    
    // Add animation
    messageDiv.style.opacity = '0';
    messageDiv.style.transform = 'translateY(10px)';
    setTimeout(() => {
        messageDiv.style.transition = 'all 0.3s ease-in';
        messageDiv.style.opacity = '1';
        messageDiv.style.transform = 'translateY(0)';
    }, 100);
}

function addOutgoingMessage(messageId, message, timestamp) {
    const messagesArea = document.getElementById('messages-area');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message-wrapper message-outgoing';
    
    const timeString = timestamp.toLocaleString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    
    messageDiv.innerHTML = `
        <div class="message-bubble">
            <div class="message-content">${escapeHtml(message)}</div>
            <div class="message-time">
                ${timeString}
                <span class="message-sender">You</span>
                <i class="fas fa-check text-muted" title="Sent"></i>
            </div>
        </div>
    `;
    
    messagesArea.appendChild(messageDiv);
    messagesArea.scrollTop = messagesArea.scrollHeight;
    
    // Add animation
    messageDiv.style.opacity = '0';
    messageDiv.style.transform = 'translateY(10px)';
    setTimeout(() => {
        messageDiv.style.transition = 'all 0.3s ease-in';
        messageDiv.style.opacity = '1';
        messageDiv.style.transform = 'translateY(0)';
    }, 100);
}

function playNotificationSound() {
    // Create a simple notification sound
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
    oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
    
    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.2);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
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
    fetch('<?php echo base_url('admin/send_admin_reply'); ?>', {
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
            
            // Add message to chat instantly
            addOutgoingMessage(data.message_id, message, new Date());
            
            // Update last message ID
            lastMessageId = Math.max(lastMessageId, data.message_id);
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

function sendQuickMessage(message) {
    const replyMessage = document.getElementById('reply-message');
    replyMessage.value = message;
    replyMessage.style.height = 'auto'; // Reset height to show new message
    replyMessage.focus();
    // Auto-send after a short delay
    setTimeout(() => {
        sendReply();
    }, 500);
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

<?php $this->load->view('admin/includes/footer'); ?> 