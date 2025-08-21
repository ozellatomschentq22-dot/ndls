<?php $this->load->view('admin/includes/header'); ?>

<!-- Simple jQuery Test -->
<script>
console.log('Page loading...');
if (typeof jQuery !== 'undefined') {
    console.log('jQuery is loaded, version:', jQuery.fn.jquery);
} else {
    console.log('jQuery is NOT loaded!');
}
</script>

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
        <i class="fas fa-tags me-2"></i>Customer Pricing
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('admin/customer_pricing'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
</div>

<!-- Customer Info Card -->
<div class="card mb-4 border-primary">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-2">
                    <i class="fas fa-user-tag me-2 text-primary"></i>
                    <?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?>
                </h5>
                <div class="d-flex gap-3">
                    <span class="badge bg-primary">
                        <i class="fas fa-crown me-1"></i>
                        <?php echo ucfirst($customer->customer_type ?? 'regular'); ?> Customer
                    </span>

                    <span class="badge bg-info">
                        <i class="fas fa-tag me-1"></i>
                        <?php echo count($customer_prices); ?> Custom Prices Set
                    </span>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Set custom prices or use default pricing with discount
                </small>
            </div>
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

<!-- Products Pricing Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list me-2"></i>Product Pricing Management
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Brand</th>
                        <th>Quantity</th>
                        <th>Default Price</th>
                        <th>Customer Price</th>
                        <th>Savings</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <?php 
                        $default_price = $product->price;
                        $customer_price = isset($price_map[$product->id]) ? $price_map[$product->id] : $default_price;
                        $has_custom_price = isset($price_map[$product->id]);
                        $savings = $default_price - $customer_price;
                    ?>
                    <tr class="<?php echo $has_custom_price ? 'table-success' : ''; ?>">
                        <td>
                            <div class="fw-bold"><?php echo htmlspecialchars($product->name); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($product->strength); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo htmlspecialchars($product->brand); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-primary"><?php echo $product->quantity; ?> pills</span>
                        </td>
                        <td>
                            <span class="fw-bold text-muted">$<?php echo number_format($default_price, 2); ?></span>
                        </td>
                        <td>
                            <div class="input-group input-group-sm" style="max-width: 120px;">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control customer-price-input" 
                                       data-product-id="<?php echo $product->id; ?>"
                                       data-customer-id="<?php echo $customer->id; ?>"
                                       value="<?php echo number_format($customer_price, 2); ?>"
                                       step="0.01" 
                                       min="0"
                                       style="text-align: right;">
                            </div>
                        </td>
                        <td>
                            <?php if ($savings > 0): ?>
                                <span class="text-success fw-bold">
                                    <i class="fas fa-arrow-down me-1"></i>
                                    $<?php echo number_format($savings, 2); ?>
                                </span>
                            <?php elseif ($savings < 0): ?>
                                <span class="text-danger fw-bold">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    $<?php echo number_format(abs($savings), 2); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">No change</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                                                 <button type="button" 
                                         class="btn btn-success btn-sm"
                                         onclick="savePrice(<?php echo $product->id; ?>, <?php echo $customer->id; ?>)">
                                     <i class="fas fa-save me-1"></i>Save
                                 </button>
                                <?php if ($has_custom_price): ?>
                                <button type="button" 
                                        class="btn btn-danger btn-sm remove-price-btn"
                                        data-product-id="<?php echo $product->id; ?>"
                                        data-customer-id="<?php echo $customer->id; ?>">
                                    <i class="fas fa-times me-1"></i>Reset
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

<!-- Test Buttons -->
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <button type="button" class="btn btn-warning" id="testAjaxBtn">
                    <i class="fas fa-bug me-2"></i>Test AJAX Connection
                </button>
                <div id="testResult" class="mt-2"></div>
            </div>
            <div class="col-md-4">
                <form method="POST" action="<?php echo base_url('admin/set_customer_price'); ?>" style="display: inline;">
                    <input type="hidden" name="customer_id" value="<?php echo $customer->id; ?>">
                    <input type="hidden" name="product_id" value="1">
                    <input type="hidden" name="price" value="150.00">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-paper-plane me-2"></i>Test Form Submit
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-success" onclick="alert('Simple button test works!')">
                    <i class="fas fa-check me-2"></i>Simple Button Test
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Pricing Legend -->
<div class="card">
    <div class="card-body">
        <h6 class="card-title">
            <i class="fas fa-info-circle me-2"></i>Pricing Information
        </h6>
        <div class="row">
            <div class="col-md-6">
                <ul class="list-unstyled">
                    <li><i class="fas fa-circle text-success me-2"></i>Green rows = Custom pricing set</li>
                    <li><i class="fas fa-circle text-muted me-2"></i>White rows = Using default pricing</li>
                    <li><i class="fas fa-arrow-down text-success me-2"></i>Green savings = Customer pays less</li>
                    <li><i class="fas fa-arrow-up text-danger me-2"></i>Red increase = Customer pays more</li>
                </ul>
            </div>
                         <div class="col-md-6">
                 <div class="alert alert-info">
                     <strong>Note:</strong> If no custom price is set, the customer will see the original product price. Set custom prices for each variant as needed.
                 </div>
             </div>
        </div>
    </div>
</div>

<script>
// Simple save price function
function savePrice(productId, customerId) {
    console.log('Save price called for product:', productId, 'customer:', customerId);
    
    // Get the price input
    const priceInput = document.querySelector(`input[data-product-id="${productId}"]`);
    const price = priceInput.value;
    
    console.log('Price value:', price);
    
    if (!price || price < 0) {
        alert('Please enter a valid price.');
        return;
    }
    
    // Create and submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo base_url("admin/set_customer_price"); ?>';
    
    const customerIdInput = document.createElement('input');
    customerIdInput.type = 'hidden';
    customerIdInput.name = 'customer_id';
    customerIdInput.value = customerId;
    
    const productIdInput = document.createElement('input');
    productIdInput.type = 'hidden';
    productIdInput.name = 'product_id';
    productIdInput.value = productId;
    
    const priceInputHidden = document.createElement('input');
    priceInputHidden.type = 'hidden';
    priceInputHidden.name = 'price';
    priceInputHidden.value = price;
    
    form.appendChild(customerIdInput);
    form.appendChild(productIdInput);
    form.appendChild(priceInputHidden);
    
    document.body.appendChild(form);
    form.submit();
}

$(document).ready(function() {
    console.log('Customer pricing page loaded');
    console.log('jQuery version:', $.fn.jquery);
    
    // Test AJAX connection
    $('#testAjaxBtn').on('click', function() {
        console.log('Test AJAX button clicked');
        $('#testResult').html('<div class="alert alert-info">Testing AJAX connection...</div>');
        
        $.ajax({
            url: '<?php echo base_url("admin/set_customer_price"); ?>',
            type: 'POST',
            data: {
                customer_id: <?php echo $customer->id; ?>,
                product_id: 1,
                price: 100.00
            },
            dataType: 'json',
            success: function(response) {
                console.log('Test AJAX response:', response);
                $('#testResult').html('<div class="alert alert-success">AJAX working! Response: ' + JSON.stringify(response) + '</div>');
            },
            error: function(xhr, status, error) {
                console.error('Test AJAX error:', xhr, status, error);
                $('#testResult').html('<div class="alert alert-danger">AJAX Error: ' + error + '</div>');
            }
        });
    });

    // Handle remove price button
    $('.remove-price-btn').on('click', function() {
        if (!confirm('Are you sure you want to remove the custom price? The customer will use the default pricing.')) {
            return;
        }

        const productId = $(this).data('product-id');
        const customerId = $(this).data('customer-id');

        console.log('Remove button clicked for product:', productId, 'customer:', customerId);

        // Show loading state
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Removing...');

        // Send AJAX request
        $.ajax({
            url: '<?php echo base_url("admin/remove_customer_price"); ?>',
            type: 'POST',
            data: {
                customer_id: customerId,
                product_id: productId
            },
            dataType: 'json',
            beforeSend: function() {
                console.log('Sending AJAX request to remove customer price');
            },
            success: function(response) {
                console.log('AJAX response:', response);
                if (response.success) {
                    // Show success message
                    const alertDiv = $('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        '<i class="fas fa-check-circle me-2"></i>' + response.message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    $('.main-content').prepend(alertDiv);
                    
                    // Reload page after a short delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    alert(response.message || 'An error occurred.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr, status, error);
                alert('An error occurred. Please try again.');
            },
            complete: function() {
                // Reset button state
                $(`.remove-price-btn[data-product-id="${productId}"]`).prop('disabled', false).html('<i class="fas fa-times me-1"></i>Reset');
            }
        });
    });
});
</script>
    </div>
</div>

<?php $this->load->view('admin/includes/footer'); ?> 