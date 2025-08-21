<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-plus me-2"></i>Place Order
                    </h1>
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

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-shopping-cart me-2"></i>Order Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php echo form_open('customer/place_order'); ?>
                                    
                                    <!-- Product Selection -->
                                    <div class="mb-4">
                                        <label for="product_id" class="form-label fw-bold">Select Product</label>
                                        <select name="product_id" id="product_id" class="form-select" required>
                                            <option value="">Choose a product...</option>
                                            <?php foreach ($products as $product): ?>
                                                <?php 
                                                    $customer_price = isset($customer_prices[$product->id]) ? $customer_prices[$product->id] : $product->price;
                                                    $has_custom_price = isset($customer_prices[$product->id]) && $customer_prices[$product->id] != $product->price;
                                                ?>
                                                <option value="<?php echo $product->id; ?>" 
                                                        data-price="<?php echo $customer_price; ?>"
                                                        data-original-price="<?php echo $product->price; ?>"
                                                        data-quantity="<?php echo $product->quantity; ?>"
                                                        data-name="<?php echo htmlspecialchars($product->name); ?>"
                                                        <?php echo (isset($selected_product_id) && $selected_product_id == $product->id) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($product->name); ?> - 
                                                    $<?php echo number_format($customer_price, 2); ?>
                                                    <?php if ($has_custom_price): ?>
                                                        <span class="text-success">(Custom Price)</span>
                                                    <?php endif; ?>
                                                    (<?php echo $product->quantity; ?> pills)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Product Details -->
                                    <div id="product-details" class="mb-4" style="display: none;">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6 class="mb-2">Product Information</h6>
                                                        <p class="mb-1"><strong>Name:</strong> <span id="product-name"></span></p>
                                                        <p class="mb-1"><strong>Quantity:</strong> <span id="product-quantity-info"></span> pills</p>
                                                        <p class="mb-0"><strong>Price:</strong> $<span id="product-price"></span></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="mb-2">Order Summary</h6>
                                                        <p class="mb-1"><strong>Total Amount:</strong> $<span id="total-amount"></span></p>
                                                        <p class="mb-0"><strong>Wallet Balance:</strong> $<span id="wallet-balance"><?php echo number_format($wallet ? $wallet->balance : 0, 2); ?></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shipping Address -->
                                    <div class="mb-4">
                                        <label for="shipping_address" class="form-label fw-bold">Shipping Address</label>
                                        <?php if (!empty($addresses)): ?>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <select name="shipping_address" id="shipping_address" class="form-select" required>
                                                        <option value="">Select shipping address...</option>
                                                        <?php foreach ($addresses as $address): ?>
                                                            <option value="<?php echo $address->id; ?>" <?php echo ($default_address && $default_address->id == $address->id) ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($address->address_name); ?> - 
                                                                <?php echo htmlspecialchars($address->full_name); ?>, 
                                                                <?php echo htmlspecialchars($address->address_line1); ?>, 
                                                                <?php echo htmlspecialchars($address->city); ?>, 
                                                                <?php echo htmlspecialchars($address->state); ?> <?php echo htmlspecialchars($address->postal_code); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                                        <i class="fas fa-plus me-2"></i>Add New Address
                                                    </button>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                No addresses found. 
                                                <button type="button" class="btn btn-outline-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                                    <i class="fas fa-plus me-1"></i>Add Address
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Order Notes -->
                                    <div class="mb-4">
                                        <label for="notes" class="form-label">Order Notes (Optional)</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Any special instructions or notes for your order..."></textarea>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg" <?php echo empty($addresses) ? 'disabled' : ''; ?>>
                                            <i class="fas fa-check me-2"></i>Place Order
                                        </button>
                                    </div>

                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Order Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6>How it works:</h6>
                                    <ol class="small">
                                        <li>Select a product from the dropdown</li>
                                        <li>Choose your shipping address</li>
                                        <li>Add any special notes if needed</li>
                                        <li>Click "Place Order" to confirm</li>
                                    </ol>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Important Notes:</h6>
                                    <ul class="small text-muted">
                                        <li>Each product variant has a fixed quantity</li>
                                        <li>Orders are processed within 24-48 hours</li>
                                        <li>Payment is deducted from your wallet</li>
                                        <li>You can cancel pending orders</li>
                                    </ul>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="<?php echo base_url('customer/products'); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-box me-2"></i>Browse Products
                                    </a>
                                    <a href="<?php echo base_url('customer/addresses'); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-map-marker-alt me-2"></i>Manage Addresses
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Address Modal -->
                <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addAddressModalLabel">
                                    <i class="fas fa-plus me-2"></i>Add New Address
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addAddressForm">
                                    <!-- Address Name -->
                                    <div class="mb-3">
                                        <label for="address_name" class="form-label fw-bold">Address Name <span class="text-danger">*</span></label>
                                        <input type="text" name="address_name" id="address_name" class="form-control" required placeholder="e.g., Home, Office, Vacation Home">
                                        <div class="form-text">Give this address a name for easy identification</div>
                                    </div>

                                    <!-- Full Name -->
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" id="full_name" class="form-control" required placeholder="Enter full name">
                                    </div>

                                    <!-- Address Line 1 -->
                                    <div class="mb-3">
                                        <label for="address_line1" class="form-label fw-bold">Address Line 1 <span class="text-danger">*</span></label>
                                        <input type="text" name="address_line1" id="address_line1" class="form-control" required placeholder="Street address, P.O. box, company name">
                                    </div>

                                    <!-- Address Line 2 -->
                                    <div class="mb-3">
                                        <label for="address_line2" class="form-label">Address Line 2</label>
                                        <input type="text" name="address_line2" id="address_line2" class="form-control" placeholder="Apartment, suite, unit, building, floor, etc.">
                                    </div>

                                    <!-- City -->
                                    <div class="mb-3">
                                        <label for="city" class="form-label fw-bold">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" id="city" class="form-control" required placeholder="Enter city name">
                                    </div>

                                    <!-- State -->
                                    <div class="mb-3">
                                        <label for="state" class="form-label fw-bold">State <span class="text-danger">*</span></label>
                                        <select name="state" id="state" class="form-select" required>
                                            <option value="">Select State</option>
                                            <option value="AL">Alabama</option>
                                            <option value="AK">Alaska</option>
                                            <option value="AZ">Arizona</option>
                                            <option value="AR">Arkansas</option>
                                            <option value="CA">California</option>
                                            <option value="CO">Colorado</option>
                                            <option value="CT">Connecticut</option>
                                            <option value="DE">Delaware</option>
                                            <option value="FL">Florida</option>
                                            <option value="GA">Georgia</option>
                                            <option value="HI">Hawaii</option>
                                            <option value="ID">Idaho</option>
                                            <option value="IL">Illinois</option>
                                            <option value="IN">Indiana</option>
                                            <option value="IA">Iowa</option>
                                            <option value="KS">Kansas</option>
                                            <option value="KY">Kentucky</option>
                                            <option value="LA">Louisiana</option>
                                            <option value="ME">Maine</option>
                                            <option value="MD">Maryland</option>
                                            <option value="MA">Massachusetts</option>
                                            <option value="MI">Michigan</option>
                                            <option value="MN">Minnesota</option>
                                            <option value="MS">Mississippi</option>
                                            <option value="MO">Missouri</option>
                                            <option value="MT">Montana</option>
                                            <option value="NE">Nebraska</option>
                                            <option value="NV">Nevada</option>
                                            <option value="NH">New Hampshire</option>
                                            <option value="NJ">New Jersey</option>
                                            <option value="NM">New Mexico</option>
                                            <option value="NY">New York</option>
                                            <option value="NC">North Carolina</option>
                                            <option value="ND">North Dakota</option>
                                            <option value="OH">Ohio</option>
                                            <option value="OK">Oklahoma</option>
                                            <option value="OR">Oregon</option>
                                            <option value="PA">Pennsylvania</option>
                                            <option value="RI">Rhode Island</option>
                                            <option value="SC">South Carolina</option>
                                            <option value="SD">South Dakota</option>
                                            <option value="TN">Tennessee</option>
                                            <option value="TX">Texas</option>
                                            <option value="UT">Utah</option>
                                            <option value="VT">Vermont</option>
                                            <option value="VA">Virginia</option>
                                            <option value="WA">Washington</option>
                                            <option value="WV">West Virginia</option>
                                            <option value="WI">Wisconsin</option>
                                            <option value="WY">Wyoming</option>
                                        </select>
                                    </div>

                                    <!-- Postal Code -->
                                    <div class="mb-3">
                                        <label for="postal_code" class="form-label fw-bold">Postal Code <span class="text-danger">*</span></label>
                                        <input type="text" name="postal_code" id="postal_code" class="form-control" required placeholder="Enter ZIP code">
                                    </div>

                                    <!-- Country -->
                                    <div class="mb-3">
                                        <label for="country" class="form-label fw-bold">Country <span class="text-danger">*</span></label>
                                        <input type="text" name="country" id="country" class="form-control" value="United States" required>
                                    </div>

                                    <!-- Phone -->
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter phone number">
                                    </div>

                                    <!-- Set as Default -->
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_default" id="is_default" class="form-check-input" value="1">
                                            <label for="is_default" class="form-check-label">Set as default address</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="saveAddressBtn">
                                    <i class="fas fa-save me-2"></i>Save Address
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // Handle product selection
                    document.getElementById('product_id').addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const productDetails = document.getElementById('product-details');
                        
                        if (this.value) {
                            const price = parseFloat(selectedOption.dataset.price);
                            const quantity = selectedOption.dataset.quantity;
                            const name = selectedOption.dataset.name; // Use data-name
                            
                            document.getElementById('product-name').textContent = name;
                            document.getElementById('product-quantity-info').textContent = quantity;
                            document.getElementById('product-price').textContent = price.toFixed(2);
                            document.getElementById('total-amount').textContent = price.toFixed(2);
                            
                            productDetails.style.display = 'block';
                        } else {
                            productDetails.style.display = 'none';
                        }
                    });

                    // Auto-trigger product selection if product is pre-selected
                    <?php if (isset($selected_product_id) && $selected_product_id): ?>
                    document.addEventListener('DOMContentLoaded', function() {
                        const productSelect = document.getElementById('product_id');
                        if (productSelect.value) {
                            // Trigger the change event to show product details
                            productSelect.dispatchEvent(new Event('change'));
                        }
                    });
                    <?php endif; ?>

                    // Handle address creation
                    document.getElementById('saveAddressBtn').addEventListener('click', function() {
                        const form = document.getElementById('addAddressForm');
                        const formData = new FormData(form);
                        
                        // Show loading state
                        this.disabled = true;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                        
                        fetch('<?php echo base_url('customer/create_customer_address'); ?>', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Add new address to dropdown
                                const select = document.getElementById('shipping_address');
                                const option = document.createElement('option');
                                option.value = data.address_id;
                                option.textContent = data.address_name + ' - ' + data.full_name + ', ' + 
                                                   data.address_line1 + ', ' + data.city + ', ' + 
                                                   data.state + ' ' + data.postal_code;
                                option.selected = true; // Select the new address
                                select.appendChild(option);
                                
                                // Close modal and reset form
                                const modal = bootstrap.Modal.getInstance(document.getElementById('addAddressModal'));
                                modal.hide();
                                form.reset();
                                
                                // Show success message
                                showAlert('Address added successfully!', 'success');
                                
                                // Enable submit button if it was disabled
                                const submitBtn = document.querySelector('button[type="submit"]');
                                if (submitBtn && submitBtn.disabled) {
                                    submitBtn.disabled = false;
                                }
                            } else {
                                showAlert(data.message || 'Failed to add address. Please try again.', 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('An error occurred. Please try again. Error: ' + error.message, 'danger');
                        })
                        .finally(() => {
                            // Reset button state
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-save me-2"></i>Save Address';
                        });
                    });

                    // Function to show alerts
                    function showAlert(message, type) {
                        const alertDiv = document.createElement('div');
                        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                        alertDiv.innerHTML = `
                            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        
                        // Insert alert after the page header
                        const header = document.querySelector('.d-flex.justify-content-between');
                        header.parentNode.insertBefore(alertDiv, header.nextSibling);
                        
                        // Auto-remove after 5 seconds
                        setTimeout(() => {
                            if (alertDiv.parentNode) {
                                alertDiv.remove();
                            }
                        }, 5000);
                    }

                    // Reset form when modal is closed
                    document.getElementById('addAddressModal').addEventListener('hidden.bs.modal', function() {
                        document.getElementById('addAddressForm').reset();
                    });
                </script>

<?php $this->load->view('customer/common/footer'); ?> 