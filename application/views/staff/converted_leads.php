<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-check-circle me-2 text-success"></i>Converted Leads
                </h1>
                <p class="text-muted mb-0">View all leads that have been successfully converted to customers.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/leads'); ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to Leads
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $total_converted; ?></h4>
                                <small>Total Converted</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $this_month_converted; ?></h4>
                                <small>This Month</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-calendar fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $conversion_rate; ?>%</h4>
                                <small>Conversion Rate</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-percentage fa-2x"></i>
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
                    <input type="text" class="form-control" id="searchInput" placeholder="Search converted leads...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="allConverted">All</button>
                    <button type="button" class="btn btn-outline-success" id="thisMonth">This Month</button>
                    <button type="button" class="btn btn-outline-info" id="lastMonth">Last Month</button>
                </div>
            </div>
        </div>

        <!-- Converted Leads Table -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($converted_leads)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Source</th>
                                    <th>Converted Date</th>
                                    <th>Customer Since</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($converted_leads as $lead): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-success rounded-circle">
                                                    <?php echo strtoupper(substr($lead->first_name, 0, 1) . substr($lead->last_name, 0, 1)); ?>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($lead->first_name . ' ' . $lead->last_name); ?></div>
                                                <small class="text-muted">Lead ID: <?php echo $lead->id; ?></small>
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
                                    <td><?php echo htmlspecialchars($lead->source); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($lead->converted_at)); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($lead->created_at)); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('staff/view_lead/' . $lead->id); ?>" class="btn btn-sm btn-outline-primary" title="View Lead Details">
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
                        <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No converted leads found</h5>
                        <p class="text-muted">There are no converted leads in the system yet.</p>
                        <a href="<?php echo base_url('staff/leads'); ?>" class="btn btn-warning">
                            <i class="fas fa-arrow-left me-1"></i>Back to Leads
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
            window.location.href = '<?php echo base_url('staff/converted_leads'); ?>?search=' + encodeURIComponent(searchTerm);
        }
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
    
    // Filter buttons
    document.getElementById('allConverted').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/converted_leads'); ?>';
    });
    
    document.getElementById('thisMonth').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/converted_leads'); ?>?period=this_month';
    });
    
    document.getElementById('lastMonth').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/converted_leads'); ?>?period=last_month';
    });
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 