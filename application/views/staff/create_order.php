<?php $this->load->view('staff/includes/header'); ?>

<div class="d-flex">
    <?php $this->load->view('staff/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-plus-circle me-2"></i>Create Order
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('staff/orders'); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Back to Orders
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

        <!-- Create Order Form -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4">
                            <i class="fas fa-plus-circle me-2 text-primary"></i>
                            Create New Order
                        </h4>

                        <?php echo form_open('staff/create_order'); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Customer *</label>
                                        <select class="form-select" id="user_id" name="user_id" required>
                                            <option value="">Select Customer</option>
                                            <?php foreach ($customers as $customer): ?>
                                                <option value="<?php echo $customer->id; ?>" <?php echo set_select('user_id', $customer->id); ?>>
                                                    <?php echo $customer->first_name . ' ' . $customer->last_name; ?> (<?php echo $customer->email; ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php echo form_error('user_id', '<small class="text-danger">', '</small>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="product_id" class="form-label">Product *</label>
                                        <select class="form-select" id="product_id" name="product_id" required>
                                            <option value="">Select Product</option>
                                            <?php foreach ($products as $product): ?>
                                                <?php if ($product->status === 'active'): ?>
                                                    <option value="<?php echo $product->id; ?>" <?php echo set_select('product_id', $product->id); ?>>
                                                        <?php echo $product->name; ?> - $<?php echo number_format($product->price, 2); ?> (<?php echo $product->quantity; ?> pills)
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php echo form_error('product_id', '<small class="text-danger">', '</small>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Order Notes</label>
                                <textarea class="form-control" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Any special instructions or notes for this order"><?php echo set_value('notes'); ?></textarea>
                                <?php echo form_error('notes', '<small class="text-danger">', '</small>'); ?>
                            </div>

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Order
                                </button>
                                <a href="<?php echo base_url('staff/orders'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>

            <!-- Order Info Sidebar -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Order Information
                        </h5>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Each product has a fixed quantity and price. Orders will be created with quantity = 1 product and the product's fixed price.
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> Please ensure all required fields are filled before creating the order.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('staff/includes/footer'); ?> 