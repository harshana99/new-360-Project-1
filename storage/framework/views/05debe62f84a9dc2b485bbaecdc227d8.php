

<?php $__env->startSection('title', 'Verify Email - 360WinEstate'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">Verify Your Email</h4>
                </div>
                <div class="card-body text-center">
                    <!-- Icon -->
                    <div class="mb-4">
                        <i class="bi bi-envelope-check-fill text-gold" style="font-size: 4rem;"></i>
                    </div>

                    <!-- Success/Info Messages -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('warning')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i><?php echo e(session('warning')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Message -->
                    <h5 class="mb-3">Check Your Email</h5>
                    <p class="text-muted mb-4">
                        We've sent a verification link to <strong><?php echo e(Auth::user()->email); ?></strong>. 
                        Please click the link in the email to verify your account.
                    </p>

                    <!-- Resend Verification Email -->
                    <form action="<?php echo e(route('verification.send')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise me-2"></i>Resend Verification Email
                            </button>
                        </div>
                    </form>

                    <!-- Logout Link -->
                    <div class="mt-4">
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-link text-muted text-decoration-none">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>