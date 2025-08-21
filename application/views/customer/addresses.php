<?php
$this->load->view('customer/common/header');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-map-marker-alt me-2"></i>My Addresses
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('customer/add_address'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add New Address
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

<!-- Addresses List -->
<?php if (!empty($addresses)): ?>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Address List
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Icon</th>
                            <th>Address Name</th>
                            <th>Contact Info</th>
                            <th>Full Address</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($addresses as $address): ?>
                        <tr>
                            <td class="text-center">
                                <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                            </td>
                            <td>
                                <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($address->address_name); ?></h6>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <strong><?php echo htmlspecialchars($address->full_name); ?></strong>
                                </div>
                                <?php if (!empty($address->phone)): ?>
                                <div class="text-muted small">
                                    <i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($address->phone); ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="mb-1">
                                    <?php echo htmlspecialchars($address->address_line1); ?>
                                    <?php if (!empty($address->address_line2)): ?>
                                        <br><?php echo htmlspecialchars($address->address_line2); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted small">
                                    <?php echo htmlspecialchars($address->city); ?>, 
                                    <?php echo htmlspecialchars($address->state); ?> 
                                    <?php echo htmlspecialchars($address->postal_code); ?>
                                </div>
                                <div class="text-muted small">
                                    <?php echo htmlspecialchars($address->country); ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($address->is_default): ?>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-star me-1"></i>Default
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Secondary</span>
                                <?php endif; ?>
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
                <i class="fas fa-map-marker-alt"></i>
                <h4>No Addresses Found</h4>
                <p class="text-muted">You haven't added any addresses yet.</p>
                <a href="<?php echo base_url('customer/add_address'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add Your First Address
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->load->view('customer/common/footer'); ?> 