<?php $this->load->view('admin/includes/header'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            <?php $this->load->view('admin/includes/sidebar'); ?>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="main-content">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="page-title">Add New Product</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/products'); ?>">Products</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Product</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-outline-secondary btn-action">
                            <i class="fas fa-arrow-left me-2"></i>Back to Products
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

                <!-- Add Product Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">
                                    <i class="fas fa-plus-circle me-2 text-success"></i>
                                    Add New Product
                                </h4>

                                <?php echo form_open('admin/add_product'); ?>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               value="<?php echo set_value('name'); ?>" 
                                               placeholder="Enter product name"
                                               required>
                                        <?php echo form_error('name', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="brand" class="form-label">Brand *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="brand" 
                                               name="brand" 
                                               value="<?php echo set_value('brand'); ?>" 
                                               placeholder="Enter product brand"
                                               required>
                                        <?php echo form_error('brand', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strength" class="form-label">Strength</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="strength" 
                                                       name="strength" 
                                                       value="<?php echo set_value('strength'); ?>" 
                                                       placeholder="e.g., 500mg, 10ml">
                                                <?php echo form_error('strength', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Price *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" 
                                                           class="form-control" 
                                                           id="price" 
                                                           name="price" 
                                                           value="<?php echo set_value('price'); ?>" 
                                                           step="0.01" 
                                                           min="0.01" 
                                                           placeholder="0.00"
                                                           required>
                                                </div>
                                                <small class="text-muted form-text mt-1">Fixed price per product</small>
                                                <?php echo form_error('price', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">Pills per Product *</label>
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="quantity" 
                                                       name="quantity" 
                                                       value="<?php echo set_value('quantity'); ?>" 
                                                       min="1" 
                                                       placeholder="e.g., 30"
                                                       required>
                                                <small class="text-muted form-text mt-1">Fixed number of pills included in each product</small>
                                                <?php echo form_error('quantity', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status *</label>
                                                <select class="form-select" id="status" name="status" required>
                                                    <option value="active" <?php echo set_select('status', 'active', TRUE); ?>>Active</option>
                                                    <option value="inactive" <?php echo set_select('status', 'inactive'); ?>>Inactive</option>
                                                </select>
                                                <?php echo form_error('status', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-success btn-action">
                                            <i class="fas fa-save me-2"></i>Create Product
                                        </button>
                                        <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-outline-secondary btn-action">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Product Guidelines Sidebar -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Product Guidelines
                                </h5>
                                
                                <div class="guidelines">
                                    <div class="mb-3">
                                        <h6 class="text-primary">Product Name</h6>
                                        <small class="text-muted">
                                            Use clear, descriptive names that customers can easily understand. 
                                            Keep it under 100 characters.
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-primary">Description</h6>
                                        <small class="text-muted">
                                            Provide detailed information about the product, including:
                                            <ul class="mt-2">
                                                <li>What the product is</li>
                                                <li>How to use it</li>
                                                <li>Any warnings or precautions</li>
                                                <li>Benefits and features</li>
                                            </ul>
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-primary">Strength</h6>
                                        <small class="text-muted">
                                            Specify the concentration or dosage (e.g., 500mg, 10ml, 25mg/ml).
                                            This helps customers understand the product potency.
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-primary">Price</h6>
                                        <small class="text-muted">
                                            Set the fixed price for this product. Each product has a fixed price regardless of quantity.
                                            Consider:
                                            <ul class="mt-2">
                                                <li>Cost of goods</li>
                                                <li>Market rates</li>
                                                <li>Profit margins</li>
                                            </ul>
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-primary">Pills per Product</h6>
                                        <small class="text-muted">
                                            Specify the fixed number of pills included in each product package.
                                            This is the standard quantity that customers will receive when they purchase this product.
                                            <br><br>
                                            <strong>Examples:</strong>
                                            <ul class="mt-2">
                                                <li>30 pills per bottle</li>
                                                <li>60 pills per package</li>
                                                <li>90 pills per container</li>
                                            </ul>
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6 class="text-primary">Status</h6>
                                        <small class="text-muted">
                                            <strong>Active:</strong> Product is available for purchase<br>
                                            <strong>Inactive:</strong> Product is temporarily unavailable
                                        </small>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    <strong>Tip:</strong> You can edit product details later from the products list.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 