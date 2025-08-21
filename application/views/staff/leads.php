<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-user-plus me-2 text-warning"></i>Leads
                </h1>
                <p class="text-muted mb-0">Manage potential customers and convert them to active customers.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/add_lead'); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus me-1"></i>Add Lead
                    </a>
                    <a href="<?php echo base_url('staff/converted_leads'); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-check me-1"></i>Converted Leads
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $total_leads; ?></h4>
                                <small>Total Leads</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $new_leads; ?></h4>
                                <small>New Leads</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-plus fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $contacted_leads; ?></h4>
                                <small>Contacted</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-phone fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $converted_leads; ?></h4>
                                <small>Converted</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search leads...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="allLeads">All</button>
                    <button type="button" class="btn btn-outline-success" id="newLeads">New</button>
                    <button type="button" class="btn btn-outline-info" id="contactedLeads">Contacted</button>
                </div>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($leads)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Source</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leads as $lead): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-warning rounded-circle">
                                                    <?php echo strtoupper(substr($lead->first_name, 0, 1) . substr($lead->last_name, 0, 1)); ?>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($lead->first_name . ' ' . $lead->last_name); ?></div>
                                                <small class="text-muted">ID: <?php echo $lead->id; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($lead->email); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($lead->email); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php if (!empty($lead->phone)): ?>
                                            <a href="tel:<?php echo htmlspecialchars($lead->phone); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($lead->phone); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm status-select" data-lead-id="<?php echo $lead->id; ?>">
                                            <option value="new" <?php echo $lead->status == 'new' ? 'selected' : ''; ?>>New</option>
                                            <option value="contacted" <?php echo $lead->status == 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                                            <option value="converted" <?php echo $lead->status == 'converted' ? 'selected' : ''; ?>>Converted</option>
                                        </select>
                                    </td>
                                    <td><?php echo htmlspecialchars($lead->source); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($lead->created_at)); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('staff/view_lead/' . $lead->id); ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo base_url('staff/edit_lead/' . $lead->id); ?>" class="btn btn-sm btn-outline-warning" title="Edit Lead">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($pagination)): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo $pagination; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No leads found</h5>
                        <p class="text-muted">There are no leads in the system yet.</p>
                        <a href="<?php echo base_url('staff/add_lead'); ?>" class="btn btn-warning">
                            <i class="fas fa-plus me-1"></i>Add First Lead
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    
    searchBtn.addEventListener('click', function() {
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            window.location.href = '<?php echo base_url('staff/leads'); ?>?search=' + encodeURIComponent(searchTerm);
        }
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
    
    // Filter buttons
    document.getElementById('allLeads').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/leads'); ?>';
    });
    
    document.getElementById('newLeads').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/leads'); ?>?status=new';
    });
    
    document.getElementById('contactedLeads').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/leads'); ?>?status=contacted';
    });
    
    // Status change functionality
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const leadId = this.dataset.leadId;
            const newStatus = this.value;
            
            console.log('Updating lead status:', leadId, 'to', newStatus);
            
            fetch('<?php echo base_url('staff/update_lead_status'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'lead_id=' + leadId + '&status=' + newStatus
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
                    alert.style.top = '20px';
                    alert.style.right = '20px';
                    alert.style.zIndex = '9999';
                    alert.innerHTML = `
                        Lead status updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    // Remove alert after 3 seconds
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 3000);
                } else {
                    console.error('Server error:', data.message);
                    alert('Error updating lead status: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error updating lead status: ' + error.message);
            });
        });
    });
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 