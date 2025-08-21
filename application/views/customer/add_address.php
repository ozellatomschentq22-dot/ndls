<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-plus me-2"></i>Add New Address
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
                                    <i class="fas fa-map-marker-alt me-2"></i>Address Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php echo form_open('customer/add_address'); ?>
                                    
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

                                    <!-- Submit Buttons -->
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Save Address
                                        </button>
                                        <a href="<?php echo base_url('customer/addresses'); ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
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
                                    <i class="fas fa-info-circle me-2"></i>Address Guidelines
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6>Required Fields:</h6>
                                    <ul class="small text-muted">
                                        <li>Address Name (for identification)</li>
                                        <li>Full Name</li>
                                        <li>Street Address</li>
                                        <li>City</li>
                                        <li>State</li>
                                        <li>Postal Code</li>
                                        <li>Country</li>
                                    </ul>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Tips:</h6>
                                    <ul class="small text-muted">
                                        <li>Use clear, descriptive address names</li>
                                        <li>Include apartment/suite numbers in Address Line 2</li>
                                        <li>Phone number is optional but recommended</li>
                                        <li>You can set one address as default</li>
                                    </ul>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="<?php echo base_url('customer/addresses'); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-list me-2"></i>View All Addresses
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<?php $this->load->view('customer/common/footer'); ?> 