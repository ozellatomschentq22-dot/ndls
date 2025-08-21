<?php
$this->load->view('customer/common/header');
?>

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-edit me-2"></i>Edit Address
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
                                    <i class="fas fa-map-marker-alt me-2"></i>Edit Address Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php echo form_open('customer/edit_address/' . $address->id); ?>
                                    
                                    <!-- Address Name -->
                                    <div class="mb-3">
                                        <label for="address_name" class="form-label fw-bold">Address Name <span class="text-danger">*</span></label>
                                        <input type="text" name="address_name" id="address_name" class="form-control" required 
                                               value="<?php echo set_value('address_name', $address->address_name); ?>" 
                                               placeholder="e.g., Home, Office, Vacation Home">
                                        <div class="form-text">Give this address a name for easy identification</div>
                                        <?php echo form_error('address_name', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <!-- Full Name -->
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="full_name" id="full_name" class="form-control" required 
                                               value="<?php echo set_value('full_name', $address->full_name); ?>" 
                                               placeholder="Enter full name">
                                        <?php echo form_error('full_name', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <!-- Address Line 1 -->
                                    <div class="mb-3">
                                        <label for="address_line1" class="form-label fw-bold">Address Line 1 <span class="text-danger">*</span></label>
                                        <input type="text" name="address_line1" id="address_line1" class="form-control" required 
                                               value="<?php echo set_value('address_line1', $address->address_line1); ?>" 
                                               placeholder="Street address, P.O. box, company name">
                                        <?php echo form_error('address_line1', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <!-- Address Line 2 -->
                                    <div class="mb-3">
                                        <label for="address_line2" class="form-label">Address Line 2</label>
                                        <input type="text" name="address_line2" id="address_line2" class="form-control" 
                                               value="<?php echo set_value('address_line2', $address->address_line2); ?>" 
                                               placeholder="Apartment, suite, unit, building, floor, etc.">
                                    </div>

                                    <!-- City -->
                                    <div class="mb-3">
                                        <label for="city" class="form-label fw-bold">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" id="city" class="form-control" required 
                                               value="<?php echo set_value('city', $address->city); ?>" 
                                               placeholder="Enter city name">
                                        <?php echo form_error('city', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <!-- State -->
                                    <div class="mb-3">
                                        <label for="state" class="form-label fw-bold">State <span class="text-danger">*</span></label>
                                        <select name="state" id="state" class="form-select" required>
                                            <option value="">Select State</option>
                                            <?php
                                            $states = array(
                                                'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
                                                'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
                                                'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
                                                'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
                                                'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
                                                'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
                                                'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
                                                'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
                                                'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
                                                'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
                                                'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
                                                'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
                                                'WI' => 'Wisconsin', 'WY' => 'Wyoming'
                                            );
                                            foreach ($states as $code => $name):
                                            ?>
                                                <option value="<?php echo $code; ?>" <?php echo set_select('state', $code, $address->state === $code); ?>>
                                                    <?php echo $name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php echo form_error('state', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <!-- Postal Code -->
                                    <div class="mb-3">
                                        <label for="postal_code" class="form-label fw-bold">Postal Code <span class="text-danger">*</span></label>
                                        <input type="text" name="postal_code" id="postal_code" class="form-control" required 
                                               value="<?php echo set_value('postal_code', $address->postal_code); ?>" 
                                               placeholder="Enter ZIP code">
                                        <?php echo form_error('postal_code', '<small class="text-danger">', '</small>'); ?>
                                    </div>

                                    <!-- Country -->
                                    <div class="mb-3">
                                        <label for="country" class="form-label fw-bold">Country <span class="text-danger">*</span></label>
                                        <input type="text" name="country" id="country" class="form-control" required 
                                               value="<?php echo set_value('country', $address->country); ?>">
                                    </div>

                                    <!-- Phone -->
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" id="phone" class="form-control" 
                                               value="<?php echo set_value('phone', $address->phone); ?>" 
                                               placeholder="Enter phone number">
                                    </div>

                                    <!-- Set as Default -->
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_default" id="is_default" class="form-check-input" value="1" 
                                                   <?php echo set_checkbox('is_default', '1', $address->is_default == 1); ?>>
                                            <label for="is_default" class="form-check-label">Set as default address</label>
                                        </div>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Address
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