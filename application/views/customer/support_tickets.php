<?php
$active_page = 'support_tickets';
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-ticket-alt me-2"></i>Support Tickets
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?php echo base_url('customer/create_ticket'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create New Ticket
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

                <!-- Tickets List -->
                <?php if (!empty($tickets)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Your Support Tickets
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Subject</th>
                                            <th>Category</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Replies</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tickets as $ticket): ?>
                                        <tr>
                                            <td>
                                                <strong>#<?php echo $ticket->ticket_number; ?></strong>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?php echo htmlspecialchars($ticket->subject); ?></div>
                                                <small class="text-muted"><?php echo substr(htmlspecialchars($ticket->message), 0, 50) . '...'; ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo ucfirst($ticket->category); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'success'); ?>">
                                                    <?php echo ucfirst($ticket->priority); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $ticket->status === 'open' ? 'warning' : ($ticket->status === 'in_progress' ? 'info' : 'success'); ?>">
                                                    <?php echo ucwords(str_replace('_', ' ', $ticket->status)); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo $ticket->reply_count; ?></span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo date('M j, Y H:i', strtotime($ticket->created_at)); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo base_url('customer/ticket_details/' . $ticket->id); ?>" 
                                                       class="btn btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="empty-state">
                                <i class="fas fa-ticket-alt"></i>
                                <h4>No Support Tickets Yet</h4>
                                <p class="text-muted">You haven't created any support tickets yet.</p>
                                <a href="<?php echo base_url('customer/create_ticket'); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Create Your First Ticket
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

<?php $this->load->view('customer/common/footer'); ?> 