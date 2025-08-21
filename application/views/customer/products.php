<?php
$active_page = 'products';
$this->load->view('customer/common/header');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-pills me-2"></i>Products
    </h1>
</div>

<!-- Customer Info Card -->
<?php if (isset($customer) && $customer): ?>
<div class="card mb-4 border-primary">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-2">
                    <i class="fas fa-user-tag me-2 text-primary"></i>
                    Welcome, <?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?>
                </h5>
                <div class="d-flex gap-3">
                    <span class="badge bg-primary">
                        <i class="fas fa-crown me-1"></i>
                        <?php echo ucfirst($customer->customer_type ?? 'regular'); ?> Customer
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Prices shown are personalized for your account
                </small>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

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

<!-- Product Selection -->
<?php if (!empty($grouped_products)): ?>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <label for="productSelect" class="form-label fw-bold">
                        <i class="fas fa-search me-2"></i>Select a Product:
                    </label>
                    <select class="form-select form-select-lg" id="productSelect">
                        <option value="">Choose a product to view variants...</option>
                        <?php foreach ($grouped_products as $key => $group): ?>
                        <option value="<?php echo $key; ?>" data-brand="<?php echo htmlspecialchars($group['brand']); ?>">
                            <?php echo htmlspecialchars($group['name']); ?> (<?php echo htmlspecialchars($group['strength']); ?>) - <?php echo htmlspecialchars($group['brand']); ?>
                            <?php if (isset($group['has_discount']) && $group['has_discount']): ?>
                                <span class="text-success">- Special Pricing</span>
                            <?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-2"></i>
                        Select a product to view available variants and your personalized pricing
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products List -->
    <div class="row">
        <?php foreach ($grouped_products as $key => $group): ?>
        <div class="col-12 mb-4 product-card" id="product-<?php echo $key; ?>" style="display: none;">
            <div class="card <?php echo (isset($group['has_discount']) && $group['has_discount']) ? 'border-success' : ''; ?>">
                <div class="card-header <?php echo (isset($group['has_discount']) && $group['has_discount']) ? 'bg-success text-white' : 'bg-primary text-white'; ?>">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-pills fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-1"><?php echo htmlspecialchars($group['name']); ?> (<?php echo htmlspecialchars($group['strength']); ?>)</h4>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-tag me-1"></i>
                                    Brand: <strong><?php echo htmlspecialchars($group['brand']); ?></strong>
                                </p>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="mb-1">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-building me-1"></i><?php echo htmlspecialchars($group['brand']); ?>
                                </span>
                            </div>
                            <?php if (isset($group['has_discount']) && $group['has_discount']): ?>
                            <div>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-tag me-1"></i>Special Pricing
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h6 class="text-muted mb-3">Available Variants:</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr class="border-bottom">
                                    <th>Quantity</th>
                                    <th>Original Price</th>
                                    <th>Your Price</th>
                                    <th>Cost Per Pill</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($group['variants'] as $product): ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-primary fs-6"><?php echo isset($product->quantity) ? $product->quantity : 1; ?> pills</span>
                                    </td>
                                    <td>
                                        <?php if (isset($product->has_discount) && $product->has_discount): ?>
                                            <span class="fw-bold text-muted text-decoration-line-through">$<?php echo number_format($product->original_price, 2); ?></span>
                                        <?php else: ?>
                                            <span class="fw-bold text-success fs-5">$<?php echo number_format($product->original_price, 2); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="fw-bold <?php echo (isset($product->has_discount) && $product->has_discount) ? 'text-success' : 'text-success'; ?> fs-5">
                                            $<?php echo number_format($product->customer_price, 2); ?>
                                        </span>
                                        <?php if (isset($product->has_discount) && $product->has_discount): ?>
                                            <small class="text-success d-block">
                                                <i class="fas fa-arrow-down me-1"></i>
                                                Save $<?php echo number_format($product->discount_amount, 2); ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $quantity = isset($product->quantity) ? $product->quantity : 1;
                                        $cost_per_pill = $quantity > 0 ? $product->customer_price / $quantity : 0;
                                        ?>
                                        <span class="fw-bold text-info">$<?php echo number_format($cost_per_pill, 4); ?></span>
                                        <small class="text-muted d-block">per pill</small>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('customer/place_order?product_id=' . $product->id); ?>" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-shopping-cart me-1"></i>Order Now
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- No Product Selected Message -->
    <div id="noProductSelected" class="card">
        <div class="card-body text-center">
            <i class="fas fa-box fa-3x text-muted mb-3"></i>
            <h4>Select a Product</h4>
            <p class="text-muted">Choose a product from the dropdown above to view its available variants and pricing.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('productSelect');
            const productCards = document.querySelectorAll('.product-card');
            const noProductSelected = document.getElementById('noProductSelected');

            function showProduct(productKey) {
                // Hide all product cards
                productCards.forEach(card => {
                    card.style.display = 'none';
                });

                // Hide no product selected message
                noProductSelected.style.display = 'none';

                // Show selected product card
                if (productKey) {
                    const selectedCard = document.getElementById('product-' + productKey);
                    if (selectedCard) {
                        selectedCard.style.display = 'block';
                    }
                } else {
                    // Show no product selected message
                    noProductSelected.style.display = 'block';
                }
            }

            // Event listener for dropdown change
            productSelect.addEventListener('change', function() {
                showProduct(this.value);
            });

            // Initialize with no product selected
            showProduct('');
        });
    </script>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-box"></i>
                <h4>No Products Available</h4>
                <p class="text-muted">There are no products available at the moment.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->load->view('customer/common/footer'); ?> 