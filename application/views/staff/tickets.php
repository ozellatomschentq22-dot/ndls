<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div>
                <h1 class="h2">
                    <i class="fas fa-headset me-2 text-success"></i>Support Tickets
                </h1>
                <p class="text-muted mb-0">Manage customer support tickets and provide assistance.</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('staff/create_ticket'); ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i>Create Ticket
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $open_tickets; ?></h4>
                                <small>Open Tickets</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-circle fa-2x"></i>
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
                                <h4 class="mb-0"><?php echo $in_progress_tickets; ?></h4>
                                <small>In Progress</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
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
                                <h4 class="mb-0"><?php echo $closed_tickets; ?></h4>
                                <small>Closed</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0"><?php echo $total_tickets; ?></h4>
                                <small>Total Tickets</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-ticket-alt fa-2x"></i>
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
                    <input type="text" class="form-control" id="searchInput" placeholder="Search tickets...">
                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary" id="allTickets">All</button>
                    <button type="button" class="btn btn-outline-danger" id="openTickets">Open</button>
                    <button type="button" class="btn btn-outline-warning" id="inProgressTickets">In Progress</button>
                    <button type="button" class="btn btn-outline-success" id="closedTickets">Closed</button>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="card">
            <div class="card-body">
                <?php if (!empty($tickets)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Customer</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Last Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tickets as $ticket): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo base_url('staff/view_ticket/' . $ticket->id); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($ticket->ticket_number); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('staff/view_customer/' . $ticket->user_id); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($ticket->first_name . ' ' . $ticket->last_name); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($ticket->subject); ?></div>
                                        <small class="text-muted"><?php echo substr(htmlspecialchars($ticket->message), 0, 50) . '...'; ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $priority_colors = [
                                            'low' => 'secondary',
                                            'medium' => 'info',
                                            'high' => 'warning',
                                            'urgent' => 'danger'
                                        ];
                                        $color = isset($priority_colors[$ticket->priority]) ? $priority_colors[$ticket->priority] : 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $color; ?>">
                                            <?php echo ucfirst($ticket->priority); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $status_colors = [
                                            'open' => 'danger',
                                            'in_progress' => 'warning',
                                            'closed' => 'success'
                                        ];
                                        $color = isset($status_colors[$ticket->status]) ? $status_colors[$ticket->status] : 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $color; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $ticket->status)); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M j, Y', strtotime($ticket->created_at)); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($ticket->updated_at)); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo base_url('staff/view_ticket/' . $ticket->id); ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
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
                        <i class="fas fa-headset fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No tickets found</h5>
                        <p class="text-muted">There are no support tickets in the system yet.</p>
                        <a href="<?php echo base_url('staff/create_ticket'); ?>" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>Create First Ticket
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
            window.location.href = '<?php echo base_url('staff/tickets'); ?>?search=' + encodeURIComponent(searchTerm);
        }
    });
    
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
    
    // Filter buttons
    document.getElementById('allTickets').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/tickets'); ?>';
    });
    
    document.getElementById('openTickets').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/tickets'); ?>?status=open';
    });
    
    document.getElementById('inProgressTickets').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/tickets'); ?>?status=in_progress';
    });
    
    document.getElementById('closedTickets').addEventListener('click', function() {
        window.location.href = '<?php echo base_url('staff/tickets'); ?>?status=closed';
    });
});
</script>

<?php $this->load->view('staff/includes/footer'); ?> 