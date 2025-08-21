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
        white-space: nowrap;
    }
    
    .search-box {
        max-width: 300px;
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-user-plus me-2"></i>Leads Management
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/add_lead'); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Lead
                    </a>
                    <a href="<?php echo base_url('admin/import_leads'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-upload me-1"></i>Import
                    </a>
                    <a href="<?php echo base_url('admin/export_leads'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-download me-1"></i>Export
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
            <div class="col-md-2 mb-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['total']; ?></h4>
                                <small class="text-muted">Total Leads</small>
                            </div>
                            <div>
                                <i class="fas fa-users fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo isset($stats['new']) ? $stats['new'] : 0; ?></h4>
                                <small class="text-muted">New Leads</small>
                            </div>
                            <div>
                                <i class="fas fa-user-plus fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo isset($stats['contacted']) ? $stats['contacted'] : 0; ?></h4>
                                <small class="text-muted">Contacted</small>
                            </div>
                            <div>
                                <i class="fas fa-phone fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo isset($stats['converted']) ? $stats['converted'] : 0; ?></h4>
                                <small class="text-muted">Converted</small>
                            </div>
                            <div>
                                <a href="<?php echo base_url('admin/converted_leads'); ?>" class="text-decoration-none" title="View Converted Leads">
                                    <i class="fas fa-user-check fa-2x text-success"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo isset($stats['lost']) ? $stats['lost'] : 0; ?></h4>
                                <small class="text-muted">Lost</small>
                            </div>
                            <div>
                                <i class="fas fa-times-circle fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="card border-secondary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><?php echo $stats['conversion_rate']; ?>%</h4>
                                <small class="text-muted">Conversion Rate</small>
                            </div>
                            <div>
                                <i class="fas fa-percentage fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo base_url('admin/leads'); ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo htmlspecialchars($search_term); ?>" 
                               placeholder="Name, email, phone, city...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="new" <?php echo $current_status === 'new' ? 'selected' : ''; ?>>New</option>
                            <option value="contacted" <?php echo $current_status === 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                            <option value="qualified" <?php echo $current_status === 'qualified' ? 'selected' : ''; ?>>Qualified</option>
                            <option value="lost" <?php echo $current_status === 'lost' ? 'selected' : ''; ?>>Lost</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="<?php echo base_url('admin/leads'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>All Leads
                    <span class="badge bg-secondary ms-2"><?php echo count($leads); ?></span>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($leads)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Location</th>
                                    <th>Product Interest</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Source</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leads as $lead): ?>
                                    <tr>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($lead->created_at)); ?>
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
                                            <?php if ($lead->product_interest): ?>
                                                <small class="text-primary">
                                                    <?php echo htmlspecialchars($lead->product_interest); ?>
                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($lead->payment_method): ?>
                                                <small class="text-success">
                                                    <?php echo htmlspecialchars($lead->payment_method); ?>
                                                </small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $status_colors = [
                                                'new' => 'warning',
                                                'contacted' => 'info',
                                                'qualified' => 'primary',
                                                'converted' => 'success',
                                                'lost' => 'danger'
                                            ];
                                            $color = isset($status_colors[$lead->status]) ? $status_colors[$lead->status] : 'secondary';
                                            ?>
                                            <span class="badge bg-<?php echo $color; ?> status-badge">
                                                <?php echo ucfirst($lead->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo ucfirst($lead->source); ?>
                                            </small>
                                        </td>
                                        <td class="lead-actions">
                                            <div class="d-flex flex-column gap-1">
                                                <!-- Quick Status Change -->
                                                <select class="form-select form-select-sm status-change" 
                                                        data-lead-id="<?php echo $lead->id; ?>" 
                                                        style="min-width: 120px; font-size: 0.75rem;">
                                                    <option value="new" <?php echo $lead->status == 'new' ? 'selected' : ''; ?>>New</option>
                                                    <option value="contacted" <?php echo $lead->status == 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                                                    <option value="qualified" <?php echo $lead->status == 'qualified' ? 'selected' : ''; ?>>Qualified</option>
                                                    <option value="converted" <?php echo $lead->status == 'converted' ? 'selected' : ''; ?>>Converted</option>
                                                    <option value="lost" <?php echo $lead->status == 'lost' ? 'selected' : ''; ?>>Lost</option>
                                                </select>
                                                
                                                <!-- Action Buttons -->
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo base_url('admin/view_lead/' . $lead->id); ?>" 
                                                       class="btn btn-outline-primary" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo base_url('admin/edit_lead/' . $lead->id); ?>" 
                                                       class="btn btn-outline-secondary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($lead->status !== 'converted'): ?>
                                                        <a href="<?php echo base_url('admin/convert_lead_to_customer/' . $lead->id); ?>" 
                                                           class="btn btn-outline-success" title="Convert to Customer"
                                                           onclick="return confirm('Are you sure you want to convert this lead to a customer?')">
                                                            <i class="fas fa-user-check"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?php echo base_url('admin/delete_lead/' . $lead->id); ?>" 
                                                       class="btn btn-outline-danger" title="Delete"
                                                       onclick="return confirm('Are you sure you want to delete this lead?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <h5>No Leads Found</h5>
                        <p class="text-muted">
                            <?php if ($search_term || $current_status): ?>
                                No leads match your current filters.
                            <?php else: ?>
                                You don't have any leads yet. Start by adding your first lead.
                            <?php endif; ?>
                        </p>
                        <?php if (!$search_term && !$current_status): ?>
                            <a href="<?php echo base_url('admin/add_lead'); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add First Lead
                            </a>
                        <?php endif; ?>
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
                        <?php echo $total_leads; ?> leads
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
// Auto-submit form when status changes
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});

// Quick status change functionality
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

// Confirm delete action
function confirmDelete(leadId) {
    return confirm('Are you sure you want to delete this lead? This action cannot be undone.');
}

// Confirm convert action
function confirmConvert(leadId) {
    return confirm('Are you sure you want to convert this lead to a customer? This will create a new customer account.');
}
</script>

<?php $this->load->view('admin/includes/footer'); ?> 