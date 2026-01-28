

<?php $__env->startSection('title', 'Edit User - ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to User Details
            </a>
            <h2 class="mb-0">Edit User</h2>
            <p class="text-muted"><?php echo e($user->name); ?> (ID: #<?php echo e($user->id); ?>)</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.users.update', $user->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <!-- Basic Information -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-person-fill me-2"></i>Basic Information
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="name" 
                                    name="name" 
                                    value="<?php echo e(old('name', $user->name)); ?>" 
                                    required
                                >
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
                                <label for="email" class="form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="email" 
                                    name="email" 
                                    value="<?php echo e(old('email', $user->email)); ?>" 
                                    required
                                >
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
                                <label for="phone" class="form-label">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="phone" 
                                    name="phone" 
                                    value="<?php echo e(old('phone', $user->phone)); ?>" 
                                    required
                                >
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
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input 
                                    type="date" 
                                    class="form-control <?php $__errorArgs = ['date_of_birth'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="date_of_birth" 
                                    name="date_of_birth" 
                                    value="<?php echo e(old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '')); ?>" 
                                    max="<?php echo e(now()->subYears(18)->format('Y-m-d')); ?>"
                                >
                                <small class="text-muted">Must be at least 18 years old</small>
                                <?php $__errorArgs = ['date_of_birth'];
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
                        </div>

                        <hr class="my-4">

                        <!-- Account Settings -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-gear-fill me-2"></i>Account Settings
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="membership_type" class="form-label">
                                    Membership Type <span class="text-danger">*</span>
                                </label>
                                <select 
                                    class="form-select <?php $__errorArgs = ['membership_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="membership_type" 
                                    name="membership_type" 
                                    required
                                >
                                    <option value="">Select Type</option>
                                    <option value="owner" <?php echo e(old('membership_type', $user->membership_type) === 'owner' ? 'selected' : ''); ?>>
                                        Owner
                                    </option>
                                    <option value="investor" <?php echo e(old('membership_type', $user->membership_type) === 'investor' ? 'selected' : ''); ?>>
                                        Investor
                                    </option>
                                    <option value="marketer" <?php echo e(old('membership_type', $user->membership_type) === 'marketer' ? 'selected' : ''); ?>>
                                        Marketer
                                    </option>
                                </select>
                                <?php $__errorArgs = ['membership_type'];
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
                                <label for="status" class="form-label">
                                    Account Status <span class="text-danger">*</span>
                                </label>
                                <select 
                                    class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="status" 
                                    name="status" 
                                    required
                                >
                                    <option value="registered" <?php echo e(old('status', $user->status) === 'registered' ? 'selected' : ''); ?>>
                                        Registered
                                    </option>
                                    <option value="membership_selected" <?php echo e(old('status', $user->status) === 'membership_selected' ? 'selected' : ''); ?>>
                                        Membership Selected
                                    </option>
                                    <option value="kyc_submitted" <?php echo e(old('status', $user->status) === 'kyc_submitted' ? 'selected' : ''); ?>>
                                        KYC Submitted
                                    </option>
                                    <option value="under_review" <?php echo e(old('status', $user->status) === 'under_review' ? 'selected' : ''); ?>>
                                        Under Review
                                    </option>
                                    <option value="approved" <?php echo e(old('status', $user->status) === 'approved' ? 'selected' : ''); ?>>
                                        Approved
                                    </option>
                                    <option value="rejected" <?php echo e(old('status', $user->status) === 'rejected' ? 'selected' : ''); ?>>
                                        Rejected
                                    </option>
                                    <option value="suspended" <?php echo e(old('status', $user->status) === 'suspended' ? 'selected' : ''); ?>>
                                        Suspended
                                    </option>
                                </select>
                                <?php $__errorArgs = ['status'];
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
                        </div>

                        <hr class="my-4">

                        <!-- Address Information -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-geo-alt-fill me-2"></i>Address Information
                        </h6>

                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address</label>
                            <textarea 
                                class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="address" 
                                name="address" 
                                rows="2"
                            ><?php echo e(old('address', $user->address)); ?></textarea>
                            <?php $__errorArgs = ['address'];
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input 
                                    type="text" 
                                    class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="city" 
                                    name="city" 
                                    value="<?php echo e(old('city', $user->city)); ?>"
                                >
                                <?php $__errorArgs = ['city'];
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
                                <label for="state" class="form-label">State/Province</label>
                                <input 
                                    type="text" 
                                    class="form-control <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="state" 
                                    name="state" 
                                    value="<?php echo e(old('state', $user->state)); ?>"
                                >
                                <?php $__errorArgs = ['state'];
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
                                <label for="country" class="form-label">Country</label>
                                <input 
                                    type="text" 
                                    class="form-control <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="country" 
                                    name="country" 
                                    value="<?php echo e(old('country', $user->country)); ?>"
                                >
                                <?php $__errorArgs = ['country'];
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
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input 
                                    type="text" 
                                    class="form-control <?php $__errorArgs = ['postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="postal_code" 
                                    name="postal_code" 
                                    value="<?php echo e(old('postal_code', $user->postal_code)); ?>"
                                >
                                <?php $__errorArgs = ['postal_code'];
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
                        </div>

                        <hr class="my-4">

                        <!-- Bio -->
                        <h6 class="text-gold mb-3">
                            <i class="bi bi-card-text me-2"></i>Additional Information
                        </h6>

                        <div class="mb-4">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea 
                                class="form-control <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="bio" 
                                name="bio" 
                                rows="4" 
                                maxlength="500"
                                placeholder="Brief description about the user..."
                            ><?php echo e(old('bio', $user->bio)); ?></textarea>
                            <small class="text-muted">Maximum 500 characters</small>
                            <?php $__errorArgs = ['bio'];
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

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Warning Notice -->
            <div class="alert alert-warning mt-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Note:</strong> Changes to user information will be logged and the user will be notified via email.
            </div>
        </div>
    </div>
</div>

<style>
    .text-gold {
        color: #E4B400;
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>