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
    
    .table-responsive {
        width: 100% !important;
    }
    
    .table {
        width: 100% !important;
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
            <div>
                <h1 class="h2">
                    <i class="fas fa-ticket-alt me-2"></i>Support Tickets
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Support Tickets</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?php echo base_url('admin/create_ticket'); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Create Ticket
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
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-primary fw-bold small mb-1">
                                    Total Tickets
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count($tickets); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ticket-alt fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-warning">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-warning fw-bold small mb-1">
                                    Open Tickets
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($tickets, function($t) { return $t->status === 'open'; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-circle fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-info">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-info fw-bold small mb-1">
                                    In Progress
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($tickets, function($t) { return $t->status === 'in_progress'; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-uppercase text-success fw-bold small mb-1">
                                    Closed Tickets
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($tickets, function($t) { return $t->status === 'closed'; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="searchInput" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by subject or user name...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="all">All Status</option>
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="priorityFilter" class="form-label">Priority</label>
                        <select class="form-select" id="priorityFilter">
                            <option value="all">All Priorities</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="filterTickets()">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Support Tickets List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="ticketsTable">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Subject</th>
                                <th>Customer</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $ticket): ?>
                            <tr data-subject="<?php echo htmlspecialchars($ticket->subject); ?>" 
                                data-name="<?php echo htmlspecialchars($ticket->first_name . ' ' . $ticket->last_name); ?>" 
                                data-status="<?php echo $ticket->status; ?>" 
                                data-priority="<?php echo $ticket->priority; ?>" 
                                data-date="<?php echo strtotime($ticket->created_at); ?>">
                                <td>#<?php echo $ticket->id; ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($ticket->subject); ?></div>
                                    <small class="text-muted"><?php echo substr(htmlspecialchars($ticket->message), 0, 50) . '...'; ?></small>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($ticket->first_name . ' ' . $ticket->last_name); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($ticket->email); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $priorityColors = [
                                        'low' => 'secondary',
                                        'medium' => 'info',
                                        'high' => 'warning',
                                        'urgent' => 'danger'
                                    ];
                                    $priorityColor = isset($priorityColors[$ticket->priority]) ? $priorityColors[$ticket->priority] : 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $priorityColor; ?>">
                                        <?php echo ucfirst($ticket->priority); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($ticket->status === 'open'): ?>
                                        <span class="badge bg-warning">Open</span>
                                    <?php elseif ($ticket->status === 'in_progress'): ?>
                                        <span class="badge bg-info">In Progress</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Closed</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($ticket->created_at)); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo base_url('admin/view_ticket/' . $ticket->id); ?>" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($ticket->status !== 'closed'): ?>
                                            <button type="button" class="btn btn-outline-info" 
                                                    onclick="updateStatus(<?php echo $ticket->id; ?>, 'in_progress')" title="Mark In Progress">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-success" 
                                                    onclick="updateStatus(<?php echo $ticket->id; ?>, 'closed')" title="Close Ticket">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const priorityFilter = document.getElementById('priorityFilter');
const table = document.getElementById('ticketsTable');
const tbody = table.querySelector('tbody');
const rows = Array.from(tbody.querySelectorAll('tr'));

function filterTickets() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusFilterValue = statusFilter.value;
    const priorityFilterValue = priorityFilter.value;
    let filteredRows = rows.filter(row => {
        const subject = row.getAttribute('data-subject').toLowerCase();
        const name = row.getAttribute('data-name').toLowerCase();
        const status = row.getAttribute('data-status');
        const priority = row.getAttribute('data-priority');
        const matchesSearch = subject.includes(searchTerm) || name.includes(searchTerm);
        const matchesStatus = !statusFilterValue || statusFilterValue === 'all' || status === statusFilterValue;
        const matchesPriority = !priorityFilterValue || priorityFilterValue === 'all' || priority === priorityFilterValue;
        return matchesSearch && matchesStatus && matchesPriority;
    });

    // Sort by date (newest first)
    filteredRows.sort((a, b) => {
        return b.getAttribute('data-date') - a.getAttribute('data-date');
    });

    tbody.innerHTML = '';
    filteredRows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

function updateStatus(ticketId, status) {
    const statusText = status === 'in_progress' ? 'in progress' : status;
    if (confirm(`Are you sure you want to mark this ticket as ${statusText}?`)) {
        window.location.href = '<?php echo base_url('admin/update_ticket_status/'); ?>' + ticketId + '/' + status;
    }
}

// Event listeners
searchInput.addEventListener('input', filterTickets);
statusFilter.addEventListener('change', filterTickets);
priorityFilter.addEventListener('change', filterTickets);
</script>

<?php $this->load->view('admin/includes/footer'); ?> 