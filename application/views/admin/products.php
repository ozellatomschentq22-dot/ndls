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
            <h1 class="h2">
                <i class="fas fa-box me-2"></i>Manage Products
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importProductsModal">
                        <i class="fas fa-upload me-1"></i>Import
                    </button>
                    <a href="<?php echo base_url('admin/add_product'); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Product
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
                                    Total Products
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count($products); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-muted"></i>
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
                                    Active Products
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($products, function($p) { return $p->status === 'active'; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-muted"></i>
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
                                    Inactive Products
                                </div>
                                <div class="h4 mb-0 fw-bold"><?php echo count(array_filter($products, function($p) { return $p->status === 'inactive'; })); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-muted"></i>
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
                                    Total Value
                                </div>
                                <div class="h4 mb-0 fw-bold">$<?php echo number_format(array_sum(array_column($products, 'price')), 2); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-muted"></i>
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
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by product name...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sortBy" class="form-label">Sort By</label>
                        <select class="form-select" id="sortBy">
                            <option value="name">Name</option>
                            <option value="price">Price</option>
                            <option value="date">Date</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="filterProducts()">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Products List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="productsTable">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Price</th>
                                <th>Pills per Product</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr data-name="<?php echo htmlspecialchars($product->name); ?>" 
                                data-status="<?php echo $product->status; ?>" 
                                data-price="<?php echo $product->price; ?>" 
                                data-date="<?php echo strtotime($product->created_at); ?>">
                                <td>#<?php echo $product->id; ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($product->name); ?></div>
                                </td>
                                <td>
                                    <div class="text-muted"><?php echo htmlspecialchars($product->brand); ?></div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        $<?php echo number_format($product->price, 2); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo $product->quantity; ?> pills
                                    </span>
                                </td>
                                <td>
                                    <?php if ($product->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($product->created_at)); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo base_url('admin/edit_product/' . $product->id); ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-info" 
                                                onclick="duplicateProduct(<?php echo $product->id; ?>)" title="Duplicate">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-<?php echo $product->status === 'active' ? 'danger' : 'success'; ?>" 
                                                onclick="toggleProduct(<?php echo $product->id; ?>)" 
                                                title="<?php echo $product->status === 'active' ? 'Deactivate' : 'Activate'; ?>">
                                            <i class="fas fa-<?php echo $product->status === 'active' ? 'ban' : 'check'; ?>"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="deleteProduct(<?php echo $product->id; ?>)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<!-- Import Products Modal -->
<div class="modal fade" id="importProductsModal" tabindex="-1" aria-labelledby="importProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importProductsModalLabel">
                    <i class="fas fa-upload me-2"></i>Import Products
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Upload CSV File</h6>
                        <form action="<?php echo base_url('admin/import_products'); ?>" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="csv_file" class="form-label">Select CSV File</label>
                                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                                <div class="form-text">File must be in CSV format</div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="skip_duplicates" name="skip_duplicates" checked>
                                    <label class="form-check-label" for="skip_duplicates">
                                        Skip duplicate names
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="set_inactive" name="set_inactive" checked>
                                    <label class="form-check-label" for="set_inactive">
                                        Set imported products as inactive (for review)
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i>Import Products
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">CSV Format Requirements</h6>
                        <div class="alert alert-info">
                            <h6>Required Columns:</h6>
                            <ul class="mb-2">
                                <li><strong>name</strong> - Product name</li>
                                <li><strong>price</strong> - Product price (numeric)</li>
                                <li><strong>quantity</strong> - Pills per product (numeric)</li>
                            </ul>
                            <h6>Optional Columns:</h6>
                            <ul class="mb-2">
                                <li><strong>strength</strong> - Product strength (e.g., 500mg)</li>
                                <li><strong>description</strong> - Product description</li>
                                <li><strong>status</strong> - active/inactive (default: inactive)</li>
                            </ul>
                            <h6>Example:</h6>
                            <code>
                                name,price,quantity,strength,description,status<br>
                                Aspirin,9.99,30,500mg,Pain relief medication,inactive<br>
                                Ibuprofen,12.50,60,200mg,Anti-inflammatory,inactive
                            </code>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo base_url('admin/download_product_template'); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download me-1"></i>Download Template
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const sortBy = document.getElementById('sortBy');
const table = document.getElementById('productsTable');
const tbody = table.querySelector('tbody');
const rows = Array.from(tbody.querySelectorAll('tr'));

function filterProducts() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusFilterValue = statusFilter.value;
    let filteredRows = rows.filter(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const status = row.getAttribute('data-status');
        const matchesSearch = name.includes(searchTerm);
        const matchesStatus = !statusFilterValue || statusFilterValue === 'all' || status === statusFilterValue;
        return matchesSearch && matchesStatus;
    });

    // Sort rows
    const sortValue = sortBy.value;
    filteredRows.sort((a, b) => {
        if (sortValue === 'name') {
            return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
        } else if (sortValue === 'price') {
            return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
        } else if (sortValue === 'date') {
            return b.getAttribute('data-date') - a.getAttribute('data-date');
        }
        return 0;
    });

    tbody.innerHTML = '';
    filteredRows.forEach(row => tbody.appendChild(row.cloneNode(true)));
}

function toggleProduct(productId) {
    if (confirm('Are you sure you want to change this product\'s status?')) {
        window.location.href = '<?php echo base_url('admin/toggle_product/'); ?>' + productId;
    }
}

function duplicateProduct(productId) {
    if (confirm('Are you sure you want to duplicate this product? This will create a copy with "Copy" added to the name.')) {
        window.location.href = '<?php echo base_url('admin/duplicate_product/'); ?>' + productId;
    }
}

function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        window.location.href = '<?php echo base_url('admin/delete_product/'); ?>' + productId;
    }
}

// Event listeners
searchInput.addEventListener('input', filterProducts);
statusFilter.addEventListener('change', filterProducts);
sortBy.addEventListener('change', filterProducts);
</script>

<?php $this->load->view('admin/includes/footer'); ?> 