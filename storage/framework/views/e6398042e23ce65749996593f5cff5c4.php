

<?php $__env->startSection('title', 'Create New Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-navy">Grant Admin Access</h5>
                        <a href="<?php echo e(route('admin.admins')); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo e(route('admin.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Enter user details to create a new <strong>administrator account</strong>.
                        </div>

                        <!-- New Admin Details -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" required placeholder="e.g. John Doe">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" required placeholder="e.g. admin@360winestate.com">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('phone')); ?>" required placeholder="e.g. +1234567890">
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="passwordInput" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required placeholder="Min. 8 chars, mixed case & symbols">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                
                                <!-- Strength Meter -->
                                <div class="progress mt-2" style="height: 5px;">
                                    <div id="passwordStrength" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small id="passwordFeedback" class="text-muted d-block mt-1" style="font-size: 0.8rem;">
                                    Start typing to check strength...
                                </small>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const passwordInput = document.getElementById('passwordInput');
                                const strengthBar = document.getElementById('passwordStrength');
                                const feedback = document.getElementById('passwordFeedback');
                                const toggleBtn = document.getElementById('togglePassword');

                                // Toggle Password Visibility
                                toggleBtn.addEventListener('click', function() {
                                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                                    passwordInput.setAttribute('type', type);
                                    this.querySelector('i').classList.toggle('bi-eye');
                                    this.querySelector('i').classList.toggle('bi-eye-slash');
                                });

                                // Password Strength Check
                                passwordInput.addEventListener('input', function() {
                                    const val = passwordInput.value;
                                    let strength = 0;
                                    let msg = '';
                                    let color = '';

                                    if (val.length === 0) {
                                        strengthBar.style.width = '0%';
                                        feedback.textContent = 'Start typing to check strength...';
                                        feedback.className = 'text-muted d-block mt-1';
                                        return;
                                    }

                                    // Checks
                                    if (val.length >= 8) strength += 20;
                                    if (val.match(/[a-z]/)) strength += 20;
                                    if (val.match(/[A-Z]/)) strength += 20;
                                    if (val.match(/[0-9]/)) strength += 20;
                                    if (val.match(/[@$!%*#?&]/)) strength += 20;

                                    // Determine Color & Message
                                    if (strength <= 20) {
                                        color = 'bg-danger';
                                        msg = 'Very Weak';
                                        feedback.className = 'text-danger d-block mt-1 fw-bold';
                                    } else if (strength <= 40) {
                                        color = 'bg-danger';
                                        msg = 'Weak';
                                        feedback.className = 'text-danger d-block mt-1 fw-bold';
                                    } else if (strength <= 60) {
                                        color = 'bg-warning';
                                        msg = 'Medium';
                                        feedback.className = 'text-warning d-block mt-1 fw-bold';
                                    } else if (strength <= 80) {
                                        color = 'bg-info';
                                        msg = 'Strong';
                                        feedback.className = 'text-info d-block mt-1 fw-bold';
                                    } else {
                                        color = 'bg-success';
                                        msg = 'Very Strong';
                                        feedback.className = 'text-success d-block mt-1 fw-bold';
                                    }

                                    // Update UI
                                    strengthBar.style.width = strength + '%';
                                    strengthBar.className = 'progress-bar ' + color;
                                    feedback.textContent = msg;
                                });
                            });
                        </script>

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Assign Role</label>
                            <div class="row g-3">
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleKey => $roleLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-12">
                                    <div class="form-check p-3 border rounded hover-bg-light">
                                        <input class="form-check-input mt-1" type="radio" name="admin_role" id="role_<?php echo e($roleKey); ?>" value="<?php echo e($roleKey); ?>" <?php echo e(old('admin_role') == $roleKey ? 'checked' : ''); ?> required>
                                        <label class="form-check-label ms-2 d-block cursor-pointer" for="role_<?php echo e($roleKey); ?>">
                                            <span class="fw-bold d-block"><?php echo e($roleLabel); ?></span>
                                            <small class="text-muted d-block mt-1">
                                                <?php if($roleKey == 'compliance_admin'): ?>
                                                    Can review and approve KYC documents.
                                                <?php elseif($roleKey == 'finance_admin'): ?>
                                                    Can manage payments, commissions, and withdrawals.
                                                <?php elseif($roleKey == 'content_admin'): ?>
                                                    Can manage projects, listings, and updates.
                                                <?php endif; ?>
                                            </small>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php $__errorArgs = ['admin_role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-gold btn-lg">
                                <i class="bi bi-shield-lock-fill me-2"></i> Grant Admin Access
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .hover-bg-light:hover { background-color: #f8f9fa; border-color: #dee2e6 !important; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/admin/admins/create.blade.php ENDPATH**/ ?>