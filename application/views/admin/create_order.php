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
    
    @media (max-width: 768px) {
        .main-content {
            width: 100% !important;
            margin-left: 0 !important;
        }
    }
    
    .order-preview {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        height: 100%;
        min-height: 400px;
    }
    
    .order-details > div {
        margin-bottom: 1rem;
    }
    
    .order-details small {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .order-details strong {
        color: #212529;
    }
    
    .order-details .text-primary {
        color: #0d6efd !important;
    }
    
    .order-details hr {
        margin: 1rem 0;
        border-color: #dee2e6;
    }
    
    /* USA Address Validation Styles */
    .usa-validation.is-valid {
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }
    
    .usa-validation.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
    
    .validation-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
    }
    
    .validation-feedback.is-valid {
        color: #198754;
    }
    
    .validation-feedback.is-invalid {
        color: #dc3545;
    }
    
    /* Address Modal Styles */
    #addAddressModal .modal-dialog {
        max-width: 800px;
    }
    
    #addAddressModal .form-label {
        font-weight: 500;
        color: #495057;
    }
    
    #addAddressModal .form-control:focus,
    #addAddressModal .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .order-preview {
            margin-top: 2rem;
            min-height: 300px;
        }
        
        #addAddressModal .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }
    }
</style>

<div class="d-flex">
    <?php $this->load->view('admin/includes/sidebar'); ?>
    
    <div class="main-content">
        <!-- Page Header -->
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">
                <i class="fas fa-plus-circle me-2"></i>Create Order
            </h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-outline-secondary btn-sm">
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

                                <?php echo form_open('admin/create_order'); ?>
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
                                                            <option value="<?php echo $product->id; ?>" 
                                                                    data-price="<?php echo $product->price; ?>"
                                                                    data-pills="<?php echo $product->quantity; ?>"
                                                                    data-strength="<?php echo $product->strength; ?>"
                                                                    <?php echo set_select('product_id', $product->id); ?>>
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
                                        <label for="address_id" class="form-label">Shipping Address *</label>
                                        <div class="d-flex gap-2">
                                            <select class="form-select" id="address_id" name="address_id" required>
                                                <option value="">Select Customer First</option>
                                            </select>
                                            <button type="button" class="btn btn-outline-primary" id="addNewAddressBtn" style="display: none;">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Select a customer to see their saved addresses or add a new one</small>
                                        <?php echo form_error('address_id', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Order Notes</label>
                                        <textarea class="form-control" 
                                                  id="notes" 
                                                  name="notes" 
                                                  rows="2" 
                                                  placeholder="Any special instructions or notes for this order"><?php echo set_value('notes'); ?></textarea>
                                        <?php echo form_error('notes', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary btn-action">
                                            <i class="fas fa-save me-2"></i>Create Order
                                        </button>
                                        <a href="<?php echo base_url('admin/orders'); ?>" class="btn btn-outline-secondary btn-action">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Order Preview Sidebar -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="fas fa-eye me-2"></i>
                                    Order Preview
                                </h5>
                                
                                <div id="orderPreview" class="order-preview">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                        <p>Select a customer and product to see order details</p>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> Each product has a fixed quantity and price. Orders will be created with quantity = 1 product and the product's fixed price.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Add New Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="newAddressForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_name" class="form-label">Address Name *</label>
                                <input type="text" class="form-control" id="address_name" name="address_name" 
                                       placeholder="e.g., Home, Work, Office" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       placeholder="Recipient's full name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address Line 1 *</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" 
                               placeholder="Street address, P.O. box, company name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2" 
                               placeholder="Apartment, suite, unit, building, floor, etc.">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       placeholder="City" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="state" class="form-label">State *</label>
                                <select class="form-select" id="state" name="state" required>
                                    <option value="">Select State</option>
                                    <option value="AL">Alabama</option>
                                    <option value="AK">Alaska</option>
                                    <option value="AZ">Arizona</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="CA">California</option>
                                    <option value="CO">Colorado</option>
                                    <option value="CT">Connecticut</option>
                                    <option value="DE">Delaware</option>
                                    <option value="DC">District of Columbia</option>
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
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="postal_code" class="form-label">ZIP Code *</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" 
                                       placeholder="12345 or 12345-6789" required maxlength="10">
                                <small class="text-muted">Format: 12345 or 12345-6789</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="country" class="form-label">Country *</label>
                                <select class="form-select" id="country" name="country" required>
                                    <option value="USA" selected>United States</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="UK">United Kingdom</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="France">France</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       placeholder="(555) 123-4567">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                            <label class="form-check-label" for="is_default">
                                Set as default address
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="saveAddressBtn">
                    <i class="fas fa-save me-2"></i>Save Address
                </button>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('user_id');
    const productSelect = document.getElementById('product_id');
    const addressSelect = document.getElementById('address_id');
    const addNewAddressBtn = document.getElementById('addNewAddressBtn');
    const orderPreview = document.getElementById('orderPreview');
    const addAddressModal = new bootstrap.Modal(document.getElementById('addAddressModal'));
    const newAddressForm = document.getElementById('newAddressForm');
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    
    let currentCustomerId = null;
    
    // Handle customer selection
    customerSelect.addEventListener('change', function() {
        const customerId = this.value;
        currentCustomerId = customerId;
        addressSelect.innerHTML = '<option value="">Loading addresses...</option>';
        
        if (customerId) {
            // Show add new address button
            addNewAddressBtn.style.display = 'block';
            
            // Load customer addresses via AJAX
            fetch(`<?php echo base_url('admin/get_customer_addresses/'); ?>${customerId}`)
                .then(response => response.json())
                .then(data => {
                    addressSelect.innerHTML = '<option value="">Select Shipping Address</option>';
                    
                    if (data.addresses && data.addresses.length > 0) {
                        data.addresses.forEach(address => {
                            const option = document.createElement('option');
                            option.value = address.id;
                            
                            // Store address data for preview
                            option.setAttribute('data-address', JSON.stringify(address));
                            
                            // Format the full address for display
                            let addressText = address.address_name;
                            if (address.is_default) {
                                addressText += ' (Default)';
                            }
                            addressText += ' - ' + address.full_name + ', ' + address.address_line1;
                            if (address.address_line2) {
                                addressText += ', ' + address.address_line2;
                            }
                            addressText += ', ' + address.city + ', ' + address.state + ' ' + address.postal_code;
                            
                            option.textContent = addressText;
                            addressSelect.appendChild(option);
                        });
                    } else {
                        addressSelect.innerHTML = '<option value="">No addresses found</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading addresses:', error);
                    addressSelect.innerHTML = '<option value="">Error loading addresses</option>';
                });
            
            // Update product options with customer-specific pricing
            updateProductOptionsWithCustomerPricing(customerId);
        } else {
            // Hide add new address button
            addNewAddressBtn.style.display = 'none';
            addressSelect.innerHTML = '<option value="">Select Customer First</option>';
            
            // Reset product options to original pricing
            resetProductOptions();
        }
        
        // Clear order preview when customer changes
        orderPreview.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <p>Select a customer and product to see order details</p>
            </div>
        `;
    });
    
    // Function to update product options with customer-specific pricing
    function updateProductOptionsWithCustomerPricing(customerId) {
        const productOptions = productSelect.querySelectorAll('option[value]');
        
        productOptions.forEach(option => {
            if (option.value) {
                const productId = option.value;
                const originalText = option.textContent;
                const originalPrice = option.getAttribute('data-price');
                
                // Show loading state
                option.textContent = originalText.split(' - ')[0] + ' - Loading...';
                
                // Fetch customer-specific price
                fetch(`<?php echo base_url('admin/get_customer_price/'); ?>${productId}/${customerId}`)
                    .then(response => response.json())
                    .then(data => {
                        const customerPrice = data.price || originalPrice;
                        const hasDiscount = data.has_discount || false;
                        
                        let newText = originalText.split(' - ')[0];
                        if (hasDiscount) {
                            newText += ` - $${parseFloat(customerPrice).toFixed(2)} (Customer Price) - ${originalText.split(' - ')[2]}`;
                        } else {
                            newText += ` - $${parseFloat(customerPrice).toFixed(2)} - ${originalText.split(' - ')[2]}`;
                        }
                        
                        option.textContent = newText;
                        option.setAttribute('data-customer-price', customerPrice);
                    })
                    .catch(error => {
                        console.error('Error fetching customer price for product:', productId, error);
                        option.textContent = originalText;
                    });
            }
        });
    }
    
    // Function to reset product options to original pricing
    function resetProductOptions() {
        const productOptions = productSelect.querySelectorAll('option[value]');
        
        productOptions.forEach(option => {
            if (option.value) {
                const originalText = option.textContent;
                const productName = originalText.split(' - ')[0];
                const originalPrice = option.getAttribute('data-price');
                const pills = originalText.split(' - ')[2];
                
                option.textContent = `${productName} - $${parseFloat(originalPrice).toFixed(2)} - ${pills}`;
                option.removeAttribute('data-customer-price');
            }
        });
    }
    
    // Handle add new address button
    addNewAddressBtn.addEventListener('click', function() {
        if (!currentCustomerId) {
            alert('Please select a customer first');
            return;
        }
        
        // Pre-fill customer name if available
        const selectedCustomer = customerSelect.options[customerSelect.selectedIndex];
        if (selectedCustomer) {
            const customerName = selectedCustomer.text.split(' (')[0]; // Get name without email
            document.getElementById('full_name').value = customerName;
        }
        
        addAddressModal.show();
    });
    
    // Handle save address button
    saveAddressBtn.addEventListener('click', function() {
        const formData = new FormData(newAddressForm);
        formData.append('user_id', currentCustomerId);
        formData.append('is_default', document.getElementById('is_default').checked ? '1' : '0');
        
        // Validate required fields
        const requiredFields = ['address_name', 'full_name', 'address_line1', 'city', 'state', 'postal_code', 'country'];
        for (let field of requiredFields) {
            if (!formData.get(field)) {
                alert('Please fill in all required fields');
                return;
            }
        }
        
        // USA Address Validation
        const country = formData.get('country');
        if (country === 'USA') {
            const validationResult = validateUSAAddress(formData);
            if (!validationResult.isValid) {
                alert(validationResult.error);
                return;
            }
        }
        
        // Disable save button and show loading
        saveAddressBtn.disabled = true;
        saveAddressBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        
        // Send AJAX request to create address
        fetch('<?php echo base_url('admin/create_customer_address'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                addAddressModal.hide();
                
                // Reset form
                newAddressForm.reset();
                
                // Reload addresses
                customerSelect.dispatchEvent(new Event('change'));
                
                // Show success message
                alert('Address created successfully!');
            } else {
                alert(data.error || 'Failed to create address');
            }
        })
        .catch(error => {
            console.error('Error creating address:', error);
            alert('Error creating address. Please try again.');
        })
        .finally(() => {
            // Re-enable save button
            saveAddressBtn.disabled = false;
            saveAddressBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Address';
        });
    });
    
    // USA Address Validation Functions
    function validateUSAAddress(formData) {
        const result = { isValid: true, error: '' };
        
        // Validate ZIP Code format
        const zipCode = formData.get('postal_code').trim();
        const zipRegex = /^\d{5}(-\d{4})?$/;
        if (!zipRegex.test(zipCode)) {
            result.isValid = false;
            result.error = 'Please enter a valid ZIP code in format: 12345 or 12345-6789';
            return result;
        }
        
        // Validate State
        const state = formData.get('state');
        if (!state) {
            result.isValid = false;
            result.error = 'Please select a valid state';
            return result;
        }
        
        // Validate Address Line 1
        const addressLine1 = formData.get('address_line1').trim();
        if (addressLine1.length < 5) {
            result.isValid = false;
            result.error = 'Address Line 1 must be at least 5 characters long';
            return result;
        }
        
        // Validate City
        const city = formData.get('city').trim();
        if (city.length < 2) {
            result.isValid = false;
            result.error = 'City name must be at least 2 characters long';
            return result;
        }
        
        // Validate Full Name
        const fullName = formData.get('full_name').trim();
        if (fullName.length < 2) {
            result.isValid = false;
            result.error = 'Full name must be at least 2 characters long';
            return result;
        }
        
        // Validate Phone Number (if provided)
        const phone = formData.get('phone').trim();
        if (phone) {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            const cleanPhone = phone.replace(/[\s\-\(\)]/g, '');
            if (!phoneRegex.test(cleanPhone) || cleanPhone.length < 10) {
                result.isValid = false;
                result.error = 'Please enter a valid phone number (at least 10 digits)';
                return result;
            }
        }
        
        return result;
    }
    
    // Real-time ZIP Code validation
    document.getElementById('postal_code').addEventListener('input', function() {
        const zipCode = this.value.trim();
        const zipRegex = /^\d{5}(-\d{4})?$/;
        
        if (zipCode && !zipRegex.test(zipCode)) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (zipCode) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else {
            this.classList.remove('is-invalid', 'is-valid');
        }
    });
    
    // Auto-format ZIP Code
    document.getElementById('postal_code').addEventListener('blur', function() {
        let zipCode = this.value.replace(/\D/g, '');
        if (zipCode.length === 9) {
            zipCode = zipCode.substring(0, 5) + '-' + zipCode.substring(5);
        }
        this.value = zipCode;
    });
    
    // Country change handler
    document.getElementById('country').addEventListener('change', function() {
        const stateSelect = document.getElementById('state');
        const zipInput = document.getElementById('postal_code');
        
        if (this.value === 'USA') {
            // Show state dropdown and ZIP code input
            stateSelect.style.display = 'block';
            zipInput.placeholder = '12345 or 12345-6789';
            zipInput.maxLength = 10;
            
            // Add validation styling
            zipInput.classList.add('usa-validation');
        } else {
            // Hide state dropdown and change ZIP to postal code
            stateSelect.style.display = 'none';
            zipInput.placeholder = 'Postal Code';
            zipInput.maxLength = 20;
            
            // Remove validation styling
            zipInput.classList.remove('usa-validation', 'is-valid', 'is-invalid');
        }
    });
    
    // Handle address selection
    addressSelect.addEventListener('change', function() {
        updateOrderPreview();
    });
    
    // Handle product selection
    productSelect.addEventListener('change', function() {
        updateOrderPreview();
    });
    
    // Function to update order preview
    function updateOrderPreview() {
        const selectedProduct = productSelect.options[productSelect.selectedIndex];
        const selectedAddress = addressSelect.options[addressSelect.selectedIndex];
        
        if (!selectedProduct.value || !currentCustomerId) {
            orderPreview.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                    <p>Select a customer and product to see order details</p>
                </div>
            `;
            return;
        }
        
        const regularPrice = selectedProduct.getAttribute('data-price');
        const customerPrice = selectedProduct.getAttribute('data-customer-price') || regularPrice;
        const pills = selectedProduct.getAttribute('data-pills');
        const strength = selectedProduct.getAttribute('data-strength');
        const productName = selectedProduct.text.split(' - ')[0];
        const productId = selectedProduct.value;
        
        // Check if we already have customer-specific pricing loaded
        const hasCustomerPricing = selectedProduct.hasAttribute('data-customer-price');
        const hasDiscount = customerPrice < regularPrice;
        
        if (!hasCustomerPricing && currentCustomerId) {
            // Show loading state
            orderPreview.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                    <p>Loading pricing information...</p>
                </div>
            `;
            
            // Fetch customer-specific price
            fetch(`<?php echo base_url('admin/get_customer_price/'); ?>${productId}/${currentCustomerId}`)
                .then(response => response.json())
                .then(data => {
                    const finalCustomerPrice = data.price || regularPrice;
                    const finalHasDiscount = data.has_discount || false;
                    const originalPrice = data.original_price || regularPrice;
                
                let previewHTML = `
                    <div class="order-details">
                        <div class="mb-3">
                            <small class="text-muted d-block">Product</small>
                            <strong>${productName}</strong>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Quantity</small>
                            <strong>1 product</strong>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Pills per Product</small>
                            <strong>${pills} pills</strong>
                        </div>
                        
                        ${strength ? `
                        <div class="mb-3">
                            <small class="text-muted d-block">Strength</small>
                            <strong>${strength}</strong>
                        </div>
                        ` : ''}
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Unit Price</small>
                            ${hasDiscount ? `
                                <div>
                                    <span class="text-decoration-line-through text-muted">$${parseFloat(originalPrice).toFixed(2)}</span>
                                    <strong class="text-primary ms-2">$${parseFloat(customerPrice).toFixed(2)}</strong>
                                    <span class="badge bg-success ms-2">Customer Price</span>
                                </div>
                            ` : `
                                <strong class="text-primary">$${parseFloat(customerPrice).toFixed(2)}</strong>
                            `}
                        </div>
                `;
                
                // Add address preview if selected
                if (selectedAddress.value) {
                    try {
                        const address = JSON.parse(selectedAddress.getAttribute('data-address'));
                        let formattedAddress = address.full_name + '<br>';
                        formattedAddress += address.address_line1;
                        if (address.address_line2) {
                            formattedAddress += '<br>' + address.address_line2;
                        }
                        formattedAddress += '<br>' + address.city + ', ' + address.state + ' ' + address.postal_code;
                        formattedAddress += '<br>' + address.country;
                        if (address.phone) {
                            formattedAddress += '<br>Phone: ' + address.phone;
                        }
                        
                        previewHTML += `
                            <hr>
                            <div class="mb-3">
                                <small class="text-muted d-block">Shipping Address</small>
                                <div class="small">
                                    <strong>${address.address_name}${address.is_default ? ' (Default)' : ''}</strong><br>
                                    ${formattedAddress}
                                </div>
                            </div>
                        `;
                    } catch (e) {
                        console.error('Error parsing address data:', e);
                    }
                }
                
                previewHTML += `
                        <hr>
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Amount</small>
                            <strong class="text-primary fs-5">$${parseFloat(customerPrice).toFixed(2)}</strong>
                        </div>
                    </div>
                `;
                
                                     orderPreview.innerHTML = previewHTML;
                 })
                 .catch(error => {
                     console.error('Error fetching customer price:', error);
                     // Fallback to regular price if API fails
                     const price = regularPrice;
                     let previewHTML = `
                         <div class="order-details">
                             <div class="mb-3">
                                 <small class="text-muted d-block">Product</small>
                                 <strong>${productName}</strong>
                             </div>
                             
                             <div class="mb-3">
                                 <small class="text-muted d-block">Quantity</small>
                                 <strong>1 product</strong>
                             </div>
                             
                             <div class="mb-3">
                                 <small class="text-muted d-block">Pills per Product</small>
                                 <strong>${pills} pills</strong>
                             </div>
                             
                             ${strength ? `
                             <div class="mb-3">
                                 <small class="text-muted d-block">Strength</small>
                                 <strong>${strength}</strong>
                             </div>
                             ` : ''}
                             
                             <div class="mb-3">
                                 <small class="text-muted d-block">Unit Price</small>
                                 <strong class="text-primary">$${parseFloat(price).toFixed(2)}</strong>
                             </div>
                     `;
                     
                     // Add address preview if selected
                     if (selectedAddress.value) {
                         try {
                             const address = JSON.parse(selectedAddress.getAttribute('data-address'));
                             let formattedAddress = address.full_name + '<br>';
                             formattedAddress += address.address_line1;
                             if (address.address_line2) {
                                 formattedAddress += '<br>' + address.address_line2;
                             }
                             formattedAddress += '<br>' + address.city + ', ' + address.state + ' ' + address.postal_code;
                             formattedAddress += '<br>' + address.country;
                             if (address.phone) {
                                 formattedAddress += '<br>Phone: ' + address.phone;
                             }
                             
                             previewHTML += `
                                 <hr>
                                 <div class="mb-3">
                                     <small class="text-muted d-block">Shipping Address</small>
                                     <div class="small">
                                         <strong>${address.address_name}${address.is_default ? ' (Default)' : ''}</strong><br>
                                         ${formattedAddress}
                                     </div>
                                 </div>
                             `;
                         } catch (e) {
                             console.error('Error parsing address data:', e);
                         }
                     }
                     
                     previewHTML += `
                             <hr>
                             <div class="mb-3">
                                 <small class="text-muted d-block">Total Amount</small>
                                 <strong class="text-primary fs-5">$${parseFloat(price).toFixed(2)}</strong>
                             </div>
                         </div>
                     `;
                     
                     orderPreview.innerHTML = previewHTML;
                 });
         } else {
             // Customer pricing already loaded or no customer selected
             let previewHTML = `
                 <div class="order-details">
                     <div class="mb-3">
                         <small class="text-muted d-block">Product</small>
                         <strong>${productName}</strong>
                     </div>
                     
                     <div class="mb-3">
                         <small class="text-muted d-block">Quantity</small>
                         <strong>1 product</strong>
                     </div>
                     
                     <div class="mb-3">
                         <small class="text-muted d-block">Pills per Product</small>
                         <strong>${pills} pills</strong>
                     </div>
                     
                     ${strength ? `
                     <div class="mb-3">
                         <small class="text-muted d-block">Strength</small>
                         <strong>${strength}</strong>
                     </div>
                     ` : ''}
                     
                     <div class="mb-3">
                         <small class="text-muted d-block">Unit Price</small>
                         ${hasDiscount ? `
                             <div>
                                 <span class="text-decoration-line-through text-muted">$${parseFloat(regularPrice).toFixed(2)}</span>
                                 <strong class="text-primary ms-2">$${parseFloat(customerPrice).toFixed(2)}</strong>
                                 <span class="badge bg-success ms-2">Customer Price</span>
                             </div>
                         ` : `
                             <strong class="text-primary">$${parseFloat(customerPrice).toFixed(2)}</strong>
                         `}
                     </div>
             `;
             
             // Add address preview if selected
             if (selectedAddress.value) {
                 try {
                     const address = JSON.parse(selectedAddress.getAttribute('data-address'));
                     let formattedAddress = address.full_name + '<br>';
                     formattedAddress += address.address_line1;
                     if (address.address_line2) {
                         formattedAddress += '<br>' + address.address_line2;
                     }
                     formattedAddress += '<br>' + address.city + ', ' + address.state + ' ' + address.postal_code;
                     formattedAddress += '<br>' + address.country;
                     if (address.phone) {
                         formattedAddress += '<br>Phone: ' + address.phone;
                     }
                     
                     previewHTML += `
                         <hr>
                         <div class="mb-3">
                             <small class="text-muted d-block">Shipping Address</small>
                             <div class="small">
                                 <strong>${address.address_name}${address.is_default ? ' (Default)' : ''}</strong><br>
                                 ${formattedAddress}
                             </div>
                         </div>
                     `;
                 } catch (e) {
                     console.error('Error parsing address data:', e);
                 }
             }
             
             previewHTML += `
                     <hr>
                     <div class="mb-3">
                         <small class="text-muted d-block">Total Amount</small>
                         <strong class="text-primary fs-5">$${parseFloat(customerPrice).toFixed(2)}</strong>
                     </div>
                 </div>
             `;
             
             orderPreview.innerHTML = previewHTML;
         }
     }
                console.error('Error fetching customer price:', error);
                // Fallback to regular price if API fails
                const price = regularPrice;
                let previewHTML = `
                    <div class="order-details">
                        <div class="mb-3">
                            <small class="text-muted d-block">Product</small>
                            <strong>${productName}</strong>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Quantity</small>
                            <strong>1 product</strong>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Pills per Product</small>
                            <strong>${pills} pills</strong>
                        </div>
                        
                        ${strength ? `
                        <div class="mb-3">
                            <small class="text-muted d-block">Strength</small>
                            <strong>${strength}</strong>
                        </div>
                        ` : ''}
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Unit Price</small>
                            <strong class="text-primary">$${parseFloat(price).toFixed(2)}</strong>
                        </div>
                `;
                
                // Add address preview if selected
                if (selectedAddress.value) {
                    try {
                        const address = JSON.parse(selectedAddress.getAttribute('data-address'));
                        let formattedAddress = address.full_name + '<br>';
                        formattedAddress += address.address_line1;
                        if (address.address_line2) {
                            formattedAddress += '<br>' + address.address_line2;
                        }
                        formattedAddress += '<br>' + address.city + ', ' + address.state + ' ' + address.postal_code;
                        formattedAddress += '<br>' + address.country;
                        if (address.phone) {
                            formattedAddress += '<br>Phone: ' + address.phone;
                        }
                        
                        previewHTML += `
                            <hr>
                            <div class="mb-3">
                                <small class="text-muted d-block">Shipping Address</small>
                                <div class="small">
                                    <strong>${address.address_name}${address.is_default ? ' (Default)' : ''}</strong><br>
                                    ${formattedAddress}
                                </div>
                            </div>
                        `;
                    } catch (e) {
                        console.error('Error parsing address data:', e);
                    }
                }
                
                previewHTML += `
                        <hr>
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Amount</small>
                            <strong class="text-primary fs-5">$${parseFloat(price).toFixed(2)}</strong>
                        </div>
                    </div>
                `;
                
                orderPreview.innerHTML = previewHTML;
            });
    }
});
</script>

<?php $this->load->view('admin/includes/footer'); ?> 