<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                        </div>
                        <h5 class="mt-3 mb-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h5>
                        <p class="text-muted mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="/Webgiay/profile" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user me-2"></i> Profile Settings
                        </a>
                        <a href="/Webgiay/orders" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-bag me-2"></i> My Orders
                        </a>
                        <a href="/Webgiay/cart" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-cart me-2"></i> Shopping Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-user me-2"></i> Profile Settings</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/Webgiay/profile" class="needs-validation" novalidate>
                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-dark border-bottom pb-2 mb-3">Personal Information</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                                <div class="invalid-feedback">Please enter your first name</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                                <div class="invalid-feedback">Please enter your last name</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" disabled>
                                <small class="form-text text-muted">Username cannot be changed</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                                <div class="invalid-feedback">Please enter a valid email address</div>
                            </div>
                        </div>
                        
                        <!-- Account Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-dark border-bottom pb-2 mb-3">Account Information</h5>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Account Type</label>
                                <input type="text" class="form-control" id="role" name="role" 
                                       value="<?php echo ucfirst($user['role'] ?? 'Customer'); ?>" disabled>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="member_since" class="form-label">Member Since</label>
                                <input type="text" class="form-control" id="member_since" name="member_since" 
                                       value="<?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?>" disabled>
                            </div>
                        </div>
                        
                        <!-- Password Change -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-dark border-bottom pb-2 mb-3">Change Password</h5>
                                <p class="text-muted mb-3">Leave password fields empty if you don't want to change your password</p>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                                <div class="invalid-feedback">Please enter your current password</div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" minlength="6">
                                <div class="invalid-feedback">Password must be at least 6 characters long</div>
                                <small class="form-text text-muted">Minimum 6 characters</small>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6">
                                <div class="invalid-feedback">Passwords do not match</div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="/Webgiay/" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back to Home
                                    </a>
                                    <button type="submit" class="btn btn-dark">
                                        <i class="fas fa-save me-2"></i> Update Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Account Stats -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-shopping-bag fa-2x text-primary mb-3"></i>                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text fs-4 fw-bold">
                                <?php echo $order_count ?? 0; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-heart fa-2x text-danger mb-3"></i>
                            <h5 class="card-title">Wishlist Items</h5>
                            <p class="card-text fs-4 fw-bold">
                                <?php 
                                // Get wishlist count - you might want to add this to the controller
                                echo "N/A"; 
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-star fa-2x text-warning mb-3"></i>
                            <h5 class="card-title">Loyalty Status</h5>
                            <p class="card-text">
                                <span class="badge bg-dark fs-6">Premium Member</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Bootstrap form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Password confirmation validation
        var newPassword = document.getElementById('new_password');
        var confirmPassword = document.getElementById('confirm_password');
        var currentPassword = document.getElementById('current_password');

        function validatePasswordChange() {
            // If new password is entered, current password is required
            if (newPassword.value && !currentPassword.value) {
                currentPassword.setCustomValidity('Current password is required to change password');
            } else {
                currentPassword.setCustomValidity('');
            }
            
            // Confirm password must match new password
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Passwords don't match");
            } else {
                confirmPassword.setCustomValidity('');
            }
        }

        if (newPassword && confirmPassword && currentPassword) {
            newPassword.addEventListener('input', validatePasswordChange);
            confirmPassword.addEventListener('input', validatePasswordChange);
            currentPassword.addEventListener('input', validatePasswordChange);
        }
    }, false);
})();
</script>
