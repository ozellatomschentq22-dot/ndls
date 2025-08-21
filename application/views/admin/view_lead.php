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
    
    .info-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .info-section h5 {
        color: #495057;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
        min-width: 150px;
    }
    
    .info-value {
        color: #212529;
        text-align: right;
        flex: 1;
        margin-left: 1rem;
    }
    
    .status-badge {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
    
    .payment-details {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 1rem;
        font-family: monospace;
        font-size: 0.875rem;
        white-space: pre-wrap;
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-user me-2"></i>Lead Details
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/edit_lead/' . $lead->id); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <?php if ($lead->status !== 'converted'): ?>
                        <a href="<?php echo base_url('admin/convert_lead_to_customer/' . $lead->id); ?>" 
                           class="btn btn-outline-success btn-sm"
                           onclick="return confirm('Are you sure you want to convert this lead to a customer? This will create a new customer account.')">
                            <i class="fas fa-user-check me-1"></i>Convert to Customer
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo base_url('admin/delete_lead/' . $lead->id); ?>" 
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this lead? This action cannot be undone.')">
                        <i class="fas fa-trash me-1"></i>Delete
                    </a>
                </div>
                <a href="<?php echo base_url('admin/leads'); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Leads
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

        <!-- Lead Information -->
        <div class="row">
            <div class="col-md-8">
                <!-- Contact Information -->
                <div class="info-section">
                    <h5><i class="fas fa-user me-2"></i>Contact Information</h5>
                    <div class="info-row">
                        <span class="info-label">Full Name:</span>
                        <span class="info-value"><?php echo htmlspecialchars($lead->first_name . ' ' . $lead->last_name); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">
                            <a href="mailto:<?php echo htmlspecialchars($lead->email); ?>">
                                <?php echo htmlspecialchars($lead->email); ?>
                            </a>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">
                            <a href="tel:<?php echo htmlspecialchars($lead->phone); ?>">
                                <?php echo htmlspecialchars($lead->phone); ?>
                            </a>
                        </span>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="info-section">
                    <h5><i class="fas fa-map-marker-alt me-2"></i>Address Information</h5>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value">
                            <?php echo htmlspecialchars($lead->address_line1); ?>
                            <?php if ($lead->address_line2): ?>
                                <br><?php echo htmlspecialchars($lead->address_line2); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">City, State, ZIP:</span>
                        <span class="info-value">
                            <?php echo htmlspecialchars($lead->city . ', ' . $lead->state . ' ' . $lead->postal_code); ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Country:</span>
                        <span class="info-value"><?php echo htmlspecialchars($lead->country); ?></span>
                    </div>
                </div>

                <!-- Product Interest -->
                <?php if ($lead->product_interest): ?>
                <div class="info-section">
                    <h5><i class="fas fa-shopping-cart me-2"></i>Product Interest</h5>
                    <div class="info-row">
                        <span class="info-label">Interest:</span>
                        <span class="info-value text-primary">
                            <?php echo htmlspecialchars($lead->product_interest); ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Payment Information -->
                <?php if ($lead->payment_method || $lead->payment_details): ?>
                <div class="info-section">
                    <h5><i class="fas fa-credit-card me-2"></i>Payment Information</h5>
                    <?php if ($lead->payment_method): ?>
                    <div class="info-row">
                        <span class="info-label">Payment Method:</span>
                        <span class="info-value text-success">
                            <?php echo htmlspecialchars($lead->payment_method); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <?php if ($lead->payment_details): ?>
                    <div class="info-row">
                        <span class="info-label">Payment Details:</span>
                        <span class="info-value">
                            <div class="payment-details"><?php echo htmlspecialchars($lead->payment_details); ?></div>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Notes -->
                <?php if ($lead->notes): ?>
                <div class="info-section">
                    <h5><i class="fas fa-sticky-note me-2"></i>Notes</h5>
                    <div class="info-row">
                        <span class="info-label">Notes:</span>
                        <span class="info-value">
                            <?php echo nl2br(htmlspecialchars($lead->notes)); ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <!-- Lead Status & Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Lead Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select status-change" data-lead-id="<?php echo $lead->id; ?>">
                                <option value="new" <?php echo $lead->status == 'new' ? 'selected' : ''; ?>>New</option>
                                <option value="contacted" <?php echo $lead->status == 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                                <option value="qualified" <?php echo $lead->status == 'qualified' ? 'selected' : ''; ?>>Qualified</option>
                                <option value="converted" <?php echo $lead->status == 'converted' ? 'selected' : ''; ?>>Converted</option>
                                <option value="lost" <?php echo $lead->status == 'lost' ? 'selected' : ''; ?>>Lost</option>
                            </select>
                            <small class="form-text text-muted">
                                <strong>New:</strong> Just added, not contacted yet<br>
                                <strong>Contacted:</strong> Initial contact made (call, email, etc.)<br>
                                <strong>Qualified:</strong> Lead shows interest and is ready to buy<br>
                                <strong>Converted:</strong> Lead became a customer<br>
                                <strong>Lost:</strong> Lead is no longer interested
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Source</label>
                            <div class="text-muted">
                                <?php echo ucfirst($lead->source); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Created</label>
                            <div class="text-muted">
                                <?php echo date('M j, Y g:i A', strtotime($lead->created_at)); ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Last Updated</label>
                            <div class="text-muted">
                                <?php echo date('M j, Y g:i A', strtotime($lead->updated_at)); ?>
                            </div>
                        </div>

                        <?php if ($lead->converted_at): ?>
                        <div class="mb-3">
                            <label class="form-label">Converted</label>
                            <div class="text-success">
                                <?php echo date('M j, Y g:i A', strtotime($lead->converted_at)); ?>
                            </div>
                        </div>

                        <?php if ($lead->converted_to_user_id): ?>
                        <div class="mb-3">
                            <label class="form-label">Customer ID</label>
                            <div>
                                <a href="<?php echo base_url('admin/view_customer/' . $lead->converted_to_user_id); ?>" 
                                   class="text-decoration-none">
                                    #<?php echo $lead->converted_to_user_id; ?>
                                    <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="mailto:<?php echo htmlspecialchars($lead->email); ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-1"></i>Send Email
                            </a>
                            <a href="tel:<?php echo htmlspecialchars($lead->phone); ?>" 
                               class="btn btn-outline-success btn-sm">
                                <i class="fas fa-phone me-1"></i>Call
                            </a>
                            <?php if ($lead->status !== 'converted'): ?>
                            <a href="<?php echo base_url('admin/convert_lead_to_customer/' . $lead->id); ?>" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Are you sure you want to convert this lead to a customer?')">
                                <i class="fas fa-user-check me-1"></i>Convert to Customer
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Confirm actions
function confirmAction(message) {
    return confirm(message);
}

// Quick status change functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('.status-change');
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const leadId = this.getAttribute('data-lead-id');
            const newStatus = this.value;
            const originalStatus = this.getAttribute('data-original-status') || this.options[this.selectedIndex - 1]?.value;
            
            // Store original status for rollback if needed
            this.setAttribute('data-original-status', originalStatus);
            
            // Show loading state
            const originalText = this.options[this.selectedIndex].text;
            this.disabled = true;
            
            // Send AJAX request
            fetch('<?php echo base_url('admin/update_lead_status'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'lead_id=' + leadId + '&status=' + newStatus
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('Status updated successfully!', 'success');
                    
                    // Update the page title or any other status indicators
                    updatePageStatus(newStatus);
                } else {
                    // Revert to original status
                    this.value = originalStatus;
                    showNotification('Failed to update status: ' + (data.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                // Revert to original status
                this.value = originalStatus;
                showNotification('Error updating status: ' + error.message, 'error');
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    }
});

// Update page status indicators
function updatePageStatus(newStatus) {
    const statusColors = {
        'new': 'warning',
        'contacted': 'info',
        'qualified': 'primary',
        'converted': 'success',
        'lost': 'danger'
    };
    
    // Update any status badges on the page
    const statusBadges = document.querySelectorAll('.status-badge');
    statusBadges.forEach(badge => {
        const color = statusColors[newStatus] || 'secondary';
        badge.className = 'badge bg-' + color + ' status-badge';
        badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    });
}

// Show notification
function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const notification = document.createElement('div');
    notification.className = 'alert ' + alertClass + ' alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="fas ${icon} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>

<?php $this->load->view('admin/includes/footer'); ?> 