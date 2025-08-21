<?php $this->load->view('admin/includes/header'); ?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-plus me-2"></i>Add Drop Shipment Order
                </h1>
                <p class="text-muted">Create a new drop shipment order</p>
            </div>
            <div>
                <a href="<?php echo base_url('dropshipment'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Orders
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Details</h6>
                    </div>
                    <div class="card-body">
                        <?php if (validation_errors()): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Please correct the following errors:
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>

                        <?php echo form_open('dropshipment/add'); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_name">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control" 
                                               value="<?php echo set_value('customer_name'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="center">Center <span class="text-danger">*</span></label>
                                        <select name="center" id="center" class="form-control" required>
                                            <option value="">Select Center</option>
                                            <?php foreach ($centers as $center): ?>
                                                <option value="<?php echo $center->name; ?>" <?php echo set_select('center', $center->name); ?>>
                                                    <?php echo $center->name; ?>
                                                    <?php if ($center->location): ?>
                                                        (<?php echo $center->location; ?>)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="customer_address">Customer Address</label>
                                <textarea name="customer_address" id="customer_address" class="form-control" rows="3"><?php echo set_value('customer_address'); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="product_name">Product Name <span class="text-danger">*</span></label>
                                        <input type="text" name="product_name" id="product_name" class="form-control" 
                                               value="<?php echo set_value('product_name'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                        <select name="quantity" id="quantity" class="form-control" required onchange="handleQuantityChange()">
                                            <option value="">Select Quantity</option>
                                            <option value="30" <?php echo set_select('quantity', '30'); ?>>30</option>
                                            <option value="60" <?php echo set_select('quantity', '60'); ?>>60</option>
                                            <option value="90" <?php echo set_select('quantity', '90'); ?>>90</option>
                                            <option value="180" <?php echo set_select('quantity', '180'); ?>>180</option>
                                            <option value="custom" <?php echo set_select('quantity', 'custom'); ?>>Custom Quantity</option>
                                        </select>
                                        <input type="number" name="custom_quantity" id="custom_quantity" class="form-control mt-2" 
                                               value="<?php echo set_value('custom_quantity'); ?>"
                                               placeholder="Enter custom quantity" min="1" style="display: <?php echo set_select('quantity', 'custom') ? 'block' : 'none'; ?>;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" 
                                          placeholder="Any additional notes about this order..."><?php echo set_value('notes'); ?></textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Create Order
                                </button>
                                <a href="<?php echo base_url('dropshipment'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4" style="position: relative; z-index: 1;">
                <div class="card shadow" id="order-info-section" style="position: relative; z-index: 1; min-height: 400px;">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
                    </div>
                    <div class="card-body" id="order-info-content" style="position: relative; z-index: 1;">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-1"></i>Order Status</h6>
                            <p class="mb-0">New orders will be created with <strong>Pending</strong> status. Admin users can then process them and set pricing.</p>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle me-1"></i>Important Notes</h6>
                            <ul class="mb-0">
                                <li>Order numbers are auto-generated</li>
                                <li>Only admin users can process orders and set prices</li>
                                <li>Tracking information can be added later</li>
                                <li>Orders are assigned to specific centers</li>
                            </ul>
                        </div>

                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-1"></i>Available Centers</h6>
                            <ul class="mb-0">
                                <?php foreach ($centers as $center): ?>
                                    <li><strong><?php echo $center->name; ?></strong>
                                        <?php if ($center->location): ?>
                                            <br><small class="text-muted"><?php echo $center->location; ?></small>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// CSS to ensure Order Information section stays visible
const style = document.createElement('style');
style.textContent = `
    #order-info-section {
        position: relative !important;
        z-index: 999 !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        min-height: 400px !important;
    }
    #order-info-content {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 999 !important;
    }
    .col-lg-4 .card {
        position: relative !important;
        z-index: 999 !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    .col-lg-4 {
        position: relative !important;
        z-index: 999 !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    /* Override any Bootstrap or other CSS that might hide elements */
    .col-lg-4 .card,
    .col-lg-4 .card-body,
    .col-lg-4 .card-header {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        position: relative !important;
        z-index: 999 !important;
    }
    /* Ensure alerts inside the card are visible */
    .col-lg-4 .alert {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
`;
document.head.appendChild(style);

// Auto-resize textareas
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(function(textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
    
    // Initialize quantity selection on page load
    handleQuantityChange();
    
    // Set required attribute for custom quantity if it's selected
    const quantitySelect = document.getElementById('quantity');
    const customQuantityInput = document.getElementById('custom_quantity');
    if (quantitySelect.value === 'custom') {
        customQuantityInput.required = true;
    }
    
    // Ensure Order Information section stays visible
    ensureOrderInfoVisible();
    
    // Monitor for any changes that might hide the Order Information section
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && 
                (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                ensureOrderInfoVisible();
            }
        });
    });
    
    const orderInfoSection = document.getElementById('order-info-section');
    if (orderInfoSection) {
        observer.observe(orderInfoSection, {
            attributes: true,
            attributeFilter: ['style', 'class']
        });
    }
});

// Function to ensure Order Information section is visible
function ensureOrderInfoVisible() {
    const orderInfoSection = document.getElementById('order-info-section');
    const orderInfoContent = document.getElementById('order-info-content');
    const orderInfoColumn = document.querySelector('.col-lg-4');
    
    if (orderInfoSection) {
        orderInfoSection.style.display = 'block';
        orderInfoSection.style.visibility = 'visible';
        orderInfoSection.style.opacity = '1';
        orderInfoSection.style.position = 'relative';
        orderInfoSection.style.zIndex = '999';
        orderInfoSection.style.minHeight = '400px';
    }
    
    if (orderInfoContent) {
        orderInfoContent.style.display = 'block';
        orderInfoContent.style.visibility = 'visible';
        orderInfoContent.style.opacity = '1';
        orderInfoContent.style.position = 'relative';
        orderInfoContent.style.zIndex = '999';
    }
    
    if (orderInfoColumn) {
        orderInfoColumn.style.display = 'block';
        orderInfoColumn.style.visibility = 'visible';
        orderInfoColumn.style.opacity = '1';
        orderInfoColumn.style.position = 'relative';
        orderInfoColumn.style.zIndex = '999';
    }
    
    // Also ensure all alerts inside are visible
    const alerts = document.querySelectorAll('.col-lg-4 .alert');
    alerts.forEach(function(alert) {
        alert.style.display = 'block';
        alert.style.visibility = 'visible';
        alert.style.opacity = '1';
    });
}

function handleQuantityChange() {
    const quantitySelect = document.getElementById('quantity');
    const customQuantityInput = document.getElementById('custom_quantity');

    if (quantitySelect.value === 'custom') {
        customQuantityInput.style.display = 'block';
        customQuantityInput.required = true;
        customQuantityInput.focus();
    } else {
        customQuantityInput.style.display = 'none';
        customQuantityInput.required = false;
        customQuantityInput.value = ''; // Clear custom quantity if a predefined option is selected
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const quantitySelect = document.getElementById('quantity');
    const customQuantityInput = document.getElementById('custom_quantity');
    
    if (quantitySelect.value === 'custom') {
        if (!customQuantityInput.value || customQuantityInput.value < 1) {
            e.preventDefault();
            alert('Please enter a valid custom quantity.');
            customQuantityInput.focus();
            return false;
        }
    } else if (!quantitySelect.value) {
        e.preventDefault();
        alert('Please select a quantity.');
        quantitySelect.focus();
        return false;
    }
});

// Periodic check to ensure Order Information section stays visible
setInterval(ensureOrderInfoVisible, 1000);
</script>

<?php $this->load->view('admin/includes/footer'); ?> 