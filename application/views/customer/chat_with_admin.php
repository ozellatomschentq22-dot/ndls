<?php
$this->load->view('customer/common/header');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-comments me-2"></i>Chat with Admin
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <span class="badge bg-success" id="status-indicator">
            <i class="fas fa-circle me-1"></i>Online
        </span>
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
        <div class="card chat-container">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="chat-avatar me-3">
                            <i class="fas fa-headset fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Admin Support</h5>
                            <small>We'll respond as soon as possible</small>
                        </div>
                    </div>
                    <div class="chat-status">
                        <span class="badge bg-success">
                            <i class="fas fa-circle me-1"></i>Online
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Messages Area -->
                <div id="messages-area" class="messages-area">
                    <?php if (empty($messages)): ?>
                        <div class="text-center text-muted mt-4">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <h5>No messages yet</h5>
                            <p>Send a message to our admin team. We'll respond as soon as possible!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($messages as $message): ?>
                            <div class="message-wrapper <?php echo isset($message->is_admin_reply) && $message->is_admin_reply ? 'message-incoming' : 'message-outgoing'; ?>">
                                <div class="message-bubble">
                                    <div class="message-content">
                                        <?php echo htmlspecialchars($message->message); ?>
                                    </div>
                                    <div class="message-time">
                                        <?php echo date('g:i A', strtotime($message->created_at)); ?>
                                        <?php if (isset($message->is_admin_reply) && $message->is_admin_reply): ?>
                                            <span class="message-sender">Admin</span>
                                        <?php endif; ?>
                                        <?php if ($message->is_read): ?>
                                            <i class="fas fa-check-double text-primary ms-1" title="Read"></i>
                                        <?php else: ?>
                                            <i class="fas fa-check text-muted ms-1" title="Sent"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Message Input Area -->
                <div class="message-input">
                    <form id="message-form">
                        <div class="input-group">
                            <textarea id="message-input" class="form-control" placeholder="Type a message..." rows="1" maxlength="1000"></textarea>
                            <button type="submit" class="btn btn-primary" id="send-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Chat Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Response Time:</h6>
                    <p class="small text-muted mb-0">
                        <i class="fas fa-stopwatch me-1"></i>
                        Usually within 2-4 hours during business hours
                    </p>
                </div>
                
                <div class="mb-3">
                    <h6>Business Hours:</h6>
                    <p class="small text-muted mb-0">
                        <i class="fas fa-clock me-1"></i>
                        Monday - Friday: 9:00 AM - 6:00 PM EST<br>
                        Saturday: 10:00 AM - 4:00 PM EST<br>
                        Sunday: Closed
                    </p>
                </div>
                
                <div class="mb-3">
                    <h6>What we can help with:</h6>
                    <ul class="small text-muted">
                        <li>Order status and tracking</li>
                        <li>Product information</li>
                        <li>Payment and billing</li>
                        <li>Account issues</li>
                        <li>General inquiries</li>
                    </ul>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="<?php echo base_url('customer/support_tickets'); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-ticket-alt me-2"></i>Create Support Ticket
                    </a>
                    <a href="<?php echo base_url('customer/orders'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-shopping-bag me-2"></i>View My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-container {
    display: flex;
    flex-direction: column;
    height: 600px;
    max-height: 600px;
    min-height: 600px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
}

.chat-container .card-body {
    display: flex;
    flex-direction: column;
    height: 100%;
    padding: 0;
    overflow: hidden;
}

.chat-avatar {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.messages-area {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    background: #e5ddd5;
    background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    height: 400px;
    max-height: 400px;
    min-height: 400px;
}

.message-wrapper {
    display: flex;
    margin-bottom: 0.5rem;
    animation: fadeIn 0.3s ease-in;
}

.message-outgoing {
    justify-content: flex-end;
}

.message-incoming {
    justify-content: flex-start;
}

.message-bubble {
    max-width: 70%;
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
}

.message-time {
    font-size: 0.75rem;
    color: #667781;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.25rem;
}

.message-sender {
    font-weight: 600;
    color: #128c7e;
    margin-right: auto;
}

.message-input {
    background: white;
    border-top: 1px solid #e9ecef;
    padding: 1rem;
    height: 80px;
    max-height: 80px;
    min-height: 80px;
    flex-shrink: 0;
}

.message-input .input-group {
    background: white;
    border-radius: 25px;
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.message-input textarea {
    border: none;
    resize: none;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    line-height: 1.4;
}

.message-input textarea:focus {
    outline: none;
    box-shadow: none;
}

.message-input .btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    margin: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
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
#message-input {
    min-height: 40px;
    max-height: 120px;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .chat-container {
        height: 500px;
    }
    
    .message-bubble {
        max-width: 85%;
    }
}
</style>

<script>
// Initialize chat
document.addEventListener('DOMContentLoaded', function() {
    const messagesArea = document.getElementById('messages-area');
    const messageInput = document.getElementById('message-input');
    const messageForm = document.getElementById('message-form');
    const sendBtn = document.getElementById('send-btn');
    
    // Scroll to bottom
    messagesArea.scrollTop = messagesArea.scrollHeight;
    
    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });
    
    // Handle form submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });
    
    // Handle Enter key (send on Enter, new line on Shift+Enter)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    // Check for admin responses every 30 seconds
    setInterval(checkAdminResponse, 30000);
    
    // Real-time message polling
    let lastMessageId = <?php echo !empty($messages) ? end($messages)->id : 0; ?>;
    setInterval(pollNewMessages, 3000); // Poll every 3 seconds
});

function pollNewMessages() {
    fetch('<?php echo base_url('customer/get_new_messages'); ?>?last_id=' + lastMessageId)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.messages && data.messages.length > 0) {
            data.messages.forEach(message => {
                addIncomingMessage(message);
                lastMessageId = Math.max(lastMessageId, message.id);
            });
            
            // Update page title if there are new messages
            if (data.messages.length > 0) {
                document.title = '(New Message) Chat with Admin - USA Pharmacy 365';
                
                // Show notification badge
                const notification = document.getElementById('admin-message-notification');
                if (notification) {
                    notification.style.display = 'inline';
                }
                
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
                <span class="message-sender">Admin</span>
                <i class="fas fa-check-double text-primary" title="Read"></i>
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

function sendMessage() {
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    // Disable input and button
    messageInput.disabled = true;
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    // Create form data
    const formData = new FormData();
    formData.append('message', message);
    
    // Send message via AJAX
    fetch('<?php echo base_url('customer/send_message_to_admin'); ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text();
    })
    .then(data => {
        console.log('Raw response:', data);
        try {
            const jsonData = JSON.parse(data);
            if (jsonData.success) {
                // Add message to chat
                addMessageToChat(message, new Date());
                messageInput.value = '';
                messageInput.style.height = 'auto';
                
                // Update last message ID
                lastMessageId = Math.max(lastMessageId, jsonData.message_id);
            } else {
                showAlert('Error: ' + jsonData.message, 'danger');
            }
        } catch (e) {
            console.error('JSON parse error:', e);
            showAlert('Invalid response from server', 'danger');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showAlert('Failed to send message. Please try again.', 'danger');
    })
    .finally(() => {
        // Re-enable input and button
        messageInput.disabled = false;
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        messageInput.focus();
    });
}

function addMessageToChat(message, timestamp) {
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
                <i class="fas fa-check text-muted" title="Sent"></i>
            </div>
        </div>
    `;
    
    messagesArea.appendChild(messageDiv);
    messagesArea.scrollTop = messagesArea.scrollHeight;
}

function checkAdminResponse() {
    fetch('<?php echo base_url('customer/check_admin_response'); ?>')
    .then(response => response.json())
    .then(data => {
        if (data.success && data.has_unread) {
            // Update page title to show unread indicator
            document.title = '(New Response) Chat with Admin - USA Pharmacy 365';
            
            // Show notification badge
            const notification = document.getElementById('admin-message-notification');
            if (notification) {
                notification.style.display = 'inline';
            }
        } else {
            document.title = 'Chat with Admin - USA Pharmacy 365';
        }
    })
    .catch(error => {
        console.error('Error checking admin response:', error);
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
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

<?php $this->load->view('customer/common/footer'); ?> 