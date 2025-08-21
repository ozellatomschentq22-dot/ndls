<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - USA Pharmacy 365</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px 0;
        }
        
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        
        .register-header {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            padding: 25px 20px;
            text-align: center;
            position: relative;
        }
        
        .register-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .register-header h4 {
            position: relative;
            z-index: 1;
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.3rem;
        }
        
        .register-header p {
            position: relative;
            z-index: 1;
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        .brand-icon {
            position: relative;
            z-index: 1;
            font-size: 2.2rem;
            margin-bottom: 12px;
            display: block;
        }
        
        .register-body {
            padding: 25px;
        }
        
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #1e40af;
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.15);
            background-color: #f8fafc;
        }
        
        .form-control.is-valid {
            border-color: #10b981;
            background-color: #f0fdf4;
        }
        
        .form-control.is-invalid {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(30, 64, 175, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }
        
        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }
        
        .divider span {
            background: white;
            padding: 0 15px;
            color: #6b7280;
            font-size: 0.85rem;
        }
        
        .field-icon {
            color: #1e40af;
            margin-right: 6px;
        }
        
        .password-requirements {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            margin-top: 12px;
        }
        
        .password-requirements h6 {
            color: #374151;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }
        
        .requirement {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 3px;
        }
        
        .requirement i {
            margin-right: 4px;
            font-size: 0.75rem;
        }
        
        .row.g-2 {
            --bs-gutter-x: 0.75rem;
            --bs-gutter-y: 0.75rem;
        }
        
        .mt-1 {
            margin-top: 0.5rem !important;
        }
        
        .mt-2 {
            margin-top: 1rem !important;
        }
        
        .mt-3 {
            margin-top: 1.5rem !important;
        }
        
        @media (max-width: 768px) {
            .register-body {
                padding: 20px 15px;
            }
            
            .register-header {
                padding: 20px 15px;
            }
            
            .brand-icon {
                font-size: 2rem;
            }
            
            .register-header h4 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-xl-5">
                <div class="register-card">
                    <div class="register-header">
                        <i class="fas fa-user-plus brand-icon"></i>
                        <h4 class="mb-0">Create Account</h4>
                        <p class="mb-0">Join USA Pharmacy 365 today!</p>
                    </div>
                    
                    <div class="register-body">
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php echo form_open('auth/register'); ?>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">
                                        <i class="fas fa-user field-icon"></i>First Name
                                    </label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" 
                                           value="<?php echo set_value('first_name'); ?>" 
                                           placeholder="First name" required>
                                    <?php echo form_error('first_name', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">
                                        <i class="fas fa-user field-icon"></i>Last Name
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" 
                                           value="<?php echo set_value('last_name'); ?>" 
                                           placeholder="Last name" required>
                                    <?php echo form_error('last_name', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            
                            <div class="row g-2 mt-1">
                                <div class="col-md-6">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-at field-icon"></i>Username
                                    </label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?php echo set_value('username'); ?>" 
                                           placeholder="Choose username" required>
                                    <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope field-icon"></i>Email
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo set_value('email'); ?>" 
                                           placeholder="Email address" required>
                                    <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            
                            <div class="mt-2">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone field-icon"></i>Phone Number
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo set_value('phone'); ?>" 
                                       placeholder="Phone number (e.g., +1234567890)" required>
                                <?php echo form_error('phone', '<small class="text-danger">', '</small>'); ?>
                            </div>
                            
                            <div class="row g-2 mt-1">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock field-icon"></i>Password
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Create password" required>
                                    <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label">
                                        <i class="fas fa-lock field-icon"></i>Confirm Password
                                    </label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Confirm password" required>
                                    <?php echo form_error('confirm_password', '<small class="text-danger">', '</small>'); ?>
                                </div>
                            </div>
                            
                            <div class="password-requirements">
                                <h6 class="mb-2"><i class="fas fa-shield-alt me-2"></i>Password Requirements</h6>
                                <div class="requirement"><i class="fas fa-circle"></i>At least 6 characters</div>
                                <div class="requirement"><i class="fas fa-circle"></i>Letters and numbers</div>
                                <div class="requirement"><i class="fas fa-circle"></i>Avoid common passwords</div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>
                        <?php echo form_close(); ?>
                        
                        <div class="divider">
                            <span>Already have an account?</span>
                        </div>
                        
                        <div class="text-center">
                            <a href="<?php echo base_url('auth/login'); ?>" class="btn btn-success">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Phone number validation and formatting
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Remove all non-digit characters except +
            value = value.replace(/[^\d+]/g, '');
            
            // Ensure only one + at the beginning
            if (value.startsWith('+')) {
                value = '+' + value.substring(1).replace(/\+/g, '');
            }
            
            // Limit length
            if (value.length > 16) {
                value = value.substring(0, 16);
            }
            
            e.target.value = value;
            
            // Add visual feedback
            if (value.length > 0) {
                const isValid = /^[\+]?[1-9][\d]{0,15}$/.test(value);
                if (isValid) {
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                } else {
                    e.target.classList.remove('is-valid');
                    e.target.classList.add('is-invalid');
                }
            } else {
                e.target.classList.remove('is-valid', 'is-invalid');
            }
        });
        
        // Form validation before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            
            if (!phoneRegex.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid phone number (e.g., +1234567890 or 1234567890)');
                document.getElementById('phone').focus();
                return false;
            }
        });
    </script>
</body>
</html> 