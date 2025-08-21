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
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .lead-actions {
        min-width: 200px;
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-user-check me-2"></i>Converted Leads
                </h1>
                <p class="text-muted mb-0">View all leads that have been converted to customers. You can edit lead information using the edit button in the actions column.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/leads'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Leads
                    </a>
                    <a href="<?php echo base_url('admin/add_lead'); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add New Lead
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['total']; ?></h4>
                                <small class="text-muted">Total Converted</small>
                            </div>
                            <div>
                                <i class="fas fa-user-check fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['today']; ?></h4>
                                <small class="text-muted">Converted Today</small>
                            </div>
                            <div>
                                <i class="fas fa-calendar-day fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['this_week']; ?></h4>
                                <small class="text-muted">This Week</small>
                            </div>
                            <div>
                                <i class="fas fa-calendar-week fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['this_month']; ?></h4>
                                <small class="text-muted">This Month</small>
                            </div>
                            <div>
                                <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo base_url('admin/converted_leads'); ?>" class="row g-3">
                    <div class="col-md-8">
                        <label for="search" class="form-label">Search Converted Leads</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo htmlspecialchars($search_term); ?>" 
                               placeholder="Name, email, phone, city, product interest...">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="<?php echo base_url('admin/converted_leads'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Converted Leads Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Converted Leads
                    <span class="badge bg-secondary ms-2"><?php echo count($leads); ?></span>
                </h5>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Use the edit button <i class="fas fa-edit"></i> to modify lead information
                </small>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($leads)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Converted Date</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Location</th>
                                    <th>Product Interest</th>
                                    <th>Payment</th>
                                    <th>Customer ID</th>
                                    <th>Source</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leads as $lead): ?>
                                    <tr>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($lead->converted_at)); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($lead->first_name . ' ' . $lead->last_name); ?></strong>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="mailto:<?php echo htmlspecialchars($lead->email); ?>" 
                                                   class="text-decoration-none" title="Click to email">
                                                    <small class="text-primary">
                                                        <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($lead->email); ?>
                                                    </small>
                                                </a><br>
                                                <a href="tel:<?php echo htmlspecialchars($lead->phone); ?>" 
                                                   class="text-decoration-none" title="Click to call">
                                                    <small class="text-success">
                                                        <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($lead->phone); ?>
                                                    </small>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <small>
                                                <?php echo htmlspecialchars($lead->city . ', ' . $lead->state); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-primary">
                                                <?php echo htmlspecialchars($lead->product_interest ?: 'N/A'); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-success">
                                                <?php echo htmlspecialchars($lead->payment_method ?: 'N/A'); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($lead->converted_to_user_id): ?>
                                                <a href="<?php echo base_url('admin/view_customer/' . $lead->converted_to_user_id); ?>" 
                                                   class="text-decoration-none">
                                                    <span class="badge bg-primary">#<?php echo $lead->converted_to_user_id; ?></span>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo ucfirst($lead->source); ?>
                                            </small>
                                        </td>
                                        <td class="lead-actions">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo base_url('admin/view_lead/' . $lead->id); ?>" 
                                                   class="btn btn-outline-primary" title="View Lead">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo base_url('admin/edit_lead/' . $lead->id); ?>" 
                                                   class="btn btn-outline-secondary" title="Edit Lead">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($lead->converted_to_user_id): ?>
                                                    <a href="<?php echo base_url('admin/view_customer/' . $lead->converted_to_user_id); ?>" 
                                                       class="btn btn-outline-success" title="View Customer">
                                                        <i class="fas fa-user"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No converted leads found</h5>
                        <p class="text-muted">Converted leads will appear here once leads are converted to customers.</p>
                        <a href="<?php echo base_url('admin/leads'); ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Leads
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pagination -->
        <?php if (isset($pagination) && $total_leads > $per_page): ?>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing <?php echo (($current_page - 1) * $per_page) + 1; ?> to 
                        <?php echo min($current_page * $per_page, $total_leads); ?> of 
                        <?php echo $total_leads; ?> converted leads
                    </div>
                    <div>
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Quick status change functionality (if needed for future use)
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.status-change');
    
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
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
                    // Update the status badge in the same row
                    const row = this.closest('tr');
                    const statusBadge = row.querySelector('.status-badge');
                    if (statusBadge) {
                        const statusColors = {
                            'new': 'warning',
                            'contacted': 'info',
                            'qualified': 'primary',
                            'converted': 'success',
                            'lost': 'danger'
                        };
                        const color = statusColors[newStatus] || 'secondary';
                        statusBadge.className = 'badge bg-' + color + ' status-badge';
                        statusBadge.textContent = originalText;
                    }
                    
                    // Show success message
                    showNotification('Status updated successfully!', 'success');
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
    });
});

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