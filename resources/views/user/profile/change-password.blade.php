@extends('layouts.app')

@section('title', 'Change Password - 360WinEstate')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="bi bi-shield-lock me-2"></i>Change Password
                </h2>
                <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>

            <!-- Change Password Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Update Your Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.change-password.submit') }}" method="POST" id="changePasswordForm">
                        @csrf

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                Current Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control @error('current_password') is-invalid @enderror" 
                                    id="current_password" 
                                    name="current_password" 
                                    required
                                    placeholder="Enter your current password"
                                >
                                <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                    <i class="bi bi-eye" id="currentPasswordIcon"></i>
                                </button>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control @error('new_password') is-invalid @enderror" 
                                    id="new_password" 
                                    name="new_password" 
                                    required
                                    placeholder="Enter your new password"
                                >
                                <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                    <i class="bi bi-eye" id="newPasswordIcon"></i>
                                </button>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="password-strength mt-2" id="passwordStrength" style="display: none;">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="strength-text text-muted"></small>
                            </div>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label">
                                Confirm New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="new_password_confirmation" 
                                    name="new_password_confirmation" 
                                    required
                                    placeholder="Re-enter your new password"
                                >
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                                </button>
                            </div>
                            <div class="password-match mt-2" id="passwordMatch" style="display: none;">
                                <small class="match-text"></small>
                            </div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading mb-2">
                                <i class="bi bi-info-circle me-2"></i>Password Requirements:
                            </h6>
                            <ul class="mb-0 ps-3" id="passwordRequirements">
                                <li id="req-length" class="text-muted">
                                    <i class="bi bi-circle"></i> At least 8 characters
                                </li>
                                <li id="req-uppercase" class="text-muted">
                                    <i class="bi bi-circle"></i> At least one uppercase letter (A-Z)
                                </li>
                                <li id="req-lowercase" class="text-muted">
                                    <i class="bi bi-circle"></i> At least one lowercase letter (a-z)
                                </li>
                                <li id="req-number" class="text-muted">
                                    <i class="bi bi-circle"></i> At least one number (0-9)
                                </li>
                                <li id="req-special" class="text-muted">
                                    <i class="bi bi-circle"></i> At least one special character (!@#$%^&*)
                                </li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="bi bi-shield-check me-1"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="alert alert-warning mt-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Security Notice:</strong> After changing your password, you'll need to use the new password for future logins. A confirmation email will be sent to your registered email address.
            </div>
        </div>
    </div>
</div>

<style>
    .password-strength .progress-bar {
        transition: width 0.3s ease, background-color 0.3s ease;
    }
    
    .requirement-met {
        color: #198754 !important;
    }
    
    .requirement-met i {
        color: #198754;
    }
    
    @media (max-width: 768px) {
        .d-flex.gap-2 {
            flex-direction: column;
        }
        
        .d-flex.gap-2 .btn {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggleButtons = [
        { btn: 'toggleCurrentPassword', input: 'current_password', icon: 'currentPasswordIcon' },
        { btn: 'toggleNewPassword', input: 'new_password', icon: 'newPasswordIcon' },
        { btn: 'toggleConfirmPassword', input: 'new_password_confirmation', icon: 'confirmPasswordIcon' }
    ];
    
    toggleButtons.forEach(item => {
        const btn = document.getElementById(item.btn);
        const input = document.getElementById(item.input);
        const icon = document.getElementById(item.icon);
        
        if (btn && input && icon) {
            btn.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }
    });
    
    // Password strength checker
    const newPassword = document.getElementById('new_password');
    const strengthIndicator = document.getElementById('passwordStrength');
    const strengthBar = strengthIndicator?.querySelector('.progress-bar');
    const strengthText = strengthIndicator?.querySelector('.strength-text');
    
    // Password requirements
    const requirements = {
        length: { regex: /.{8,}/, element: document.getElementById('req-length') },
        uppercase: { regex: /[A-Z]/, element: document.getElementById('req-uppercase') },
        lowercase: { regex: /[a-z]/, element: document.getElementById('req-lowercase') },
        number: { regex: /[0-9]/, element: document.getElementById('req-number') },
        special: { regex: /[!@#$%^&*(),.?":{}|<>]/, element: document.getElementById('req-special') }
    };
    
    newPassword?.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length > 0) {
            strengthIndicator.style.display = 'block';
            
            let strength = 0;
            let metRequirements = 0;
            
            // Check each requirement
            Object.keys(requirements).forEach(key => {
                const req = requirements[key];
                if (req.regex.test(password)) {
                    req.element.classList.add('requirement-met');
                    req.element.querySelector('i').classList.remove('bi-circle');
                    req.element.querySelector('i').classList.add('bi-check-circle-fill');
                    metRequirements++;
                } else {
                    req.element.classList.remove('requirement-met');
                    req.element.querySelector('i').classList.remove('bi-check-circle-fill');
                    req.element.querySelector('i').classList.add('bi-circle');
                }
            });
            
            // Calculate strength
            strength = (metRequirements / 5) * 100;
            
            // Update progress bar
            strengthBar.style.width = strength + '%';
            
            // Update color and text
            if (strength < 40) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Weak password';
                strengthText.className = 'strength-text text-danger';
            } else if (strength < 80) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Medium password';
                strengthText.className = 'strength-text text-warning';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Strong password';
                strengthText.className = 'strength-text text-success';
            }
        } else {
            strengthIndicator.style.display = 'none';
            // Reset all requirements
            Object.keys(requirements).forEach(key => {
                requirements[key].element.classList.remove('requirement-met');
                requirements[key].element.querySelector('i').classList.remove('bi-check-circle-fill');
                requirements[key].element.querySelector('i').classList.add('bi-circle');
            });
        }
    });
    
    // Password match checker
    const confirmPassword = document.getElementById('new_password_confirmation');
    const matchIndicator = document.getElementById('passwordMatch');
    const matchText = matchIndicator?.querySelector('.match-text');
    
    confirmPassword?.addEventListener('input', function() {
        const password = newPassword.value;
        const confirm = this.value;
        
        if (confirm.length > 0) {
            matchIndicator.style.display = 'block';
            
            if (password === confirm) {
                matchText.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>Passwords match';
                matchText.className = 'match-text text-success';
            } else {
                matchText.innerHTML = '<i class="bi bi-x-circle-fill text-danger me-1"></i>Passwords do not match';
                matchText.className = 'match-text text-danger';
            }
        } else {
            matchIndicator.style.display = 'none';
        }
    });
});
</script>
@endsection
