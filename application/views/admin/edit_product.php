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
                            <h1 class="page-title">Edit Product</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/dashboard'); ?>">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo base_url('admin/products'); ?>">Products</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
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

                <!-- Edit Product Form -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-4">
                                    <i class="fas fa-edit me-2 text-primary"></i>
                                    Edit Product Information
                                </h4>

                                <?php echo form_open('admin/edit_product/' . $product->id); ?>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               value="<?php echo set_value('name', $product->name); ?>" 
                                               required>
                                        <?php echo form_error('name', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="brand" class="form-label">Brand *</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="brand" 
                                               name="brand" 
                                               value="<?php echo set_value('brand', $product->brand); ?>" 
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
                                                       value="<?php echo set_value('strength', $product->strength); ?>" 
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
                                                           value="<?php echo set_value('price', $product->price); ?>" 
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
                                                       value="<?php echo set_value('quantity', $product->quantity); ?>" 
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
                                                    <option value="active" <?php echo set_select('status', 'active', $product->status === 'active'); ?>>Active</option>
                                                    <option value="inactive" <?php echo set_select('status', 'inactive', $product->status === 'inactive'); ?>>Inactive</option>
                                                </select>
                                                <?php echo form_error('status', '<small class="text-danger">', '</small>'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary btn-action">
                                            <i class="fas fa-save me-2"></i>Update Product
                                        </button>
                                        <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-outline-secondary btn-action">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Product Information Sidebar -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Product Information
                                </h5>
                                
                                <div class="product-info">
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Product ID</small>
                                        <strong>#<?php echo $product->id; ?></strong>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Current Name</small>
                                        <strong><?php echo $product->name; ?></strong>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Current Price</small>
                                        <strong class="text-primary">$<?php echo number_format($product->price, 2); ?></strong>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Current Stock</small>
                                        <strong><?php echo $product->quantity; ?> units</strong>
                                    </div>
                                    
                                    <?php if ($product->strength): ?>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Current Strength</small>
                                            <strong><?php echo $product->strength; ?></strong>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Status</small>
                                        <span class="badge bg-<?php echo $product->status === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($product->status); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted d-block">Created</small>
                                        <strong><?php echo isset($product->created_at) ? date('M d, Y H:i', strtotime($product->created_at)) : 'Unknown'; ?></strong>
                                    </div>
                                    
                                    <?php if (isset($product->updated_at) && $product->updated_at != $product->created_at): ?>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Last Updated</small>
                                            <strong><?php echo date('M d, Y H:i', strtotime($product->updated_at)); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <hr>
                                
                                <div class="d-grid gap-2">
                                    <a href="<?php echo base_url('admin/toggle_product/' . $product->id); ?>" 
                                       class="btn btn-outline-<?php echo $product->status === 'active' ? 'warning' : 'success'; ?> btn-sm"
                                       onclick="return confirm('<?php echo $product->status === 'active' ? 'Deactivate' : 'Activate'; ?> this product?')">
                                        <i class="fas fa-<?php echo $product->status === 'active' ? 'ban' : 'check'; ?> me-2"></i>
                                        <?php echo $product->status === 'active' ? 'Deactivate' : 'Activate'; ?> Product
                                    </a>
                                    <a href="<?php echo base_url('admin/delete_product/' . $product->id); ?>" 
                                       class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                        <i class="fas fa-trash me-2"></i>Delete Product
                                    </a>
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