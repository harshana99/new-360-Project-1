


<?php $__env->startSection('title', 'Select Membership - 360WinEstate'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center text-white mb-5">
                <h2 class="fw-bold mb-3">Choose Your Membership</h2>
                <p class="lead">Select the membership type that best suits your needs</p>
            </div>

            <!-- Success/Error Messages -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i><?php echo e(session('info')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('warning')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i><?php echo e(session('warning')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Membership Cards -->
            <form action="<?php echo e(route('membership.select')); ?>" method="POST" id="membershipForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="membership_type" id="membershipTypeInput">

                <div class="row g-4">
                    <!-- Property Owner -->
                    <div class="col-md-4">
                        <div class="card membership-card h-100" onclick="selectMembership('owner')">
                            <div class="card-body text-center p-4">
                                <div class="membership-icon mb-4">
                                    <i class="bi bi-building text-gold" style="font-size: 3.5rem;"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Property Owner</h4>
                                <p class="text-muted mb-4">
                                    List and manage your properties on our platform. Connect with investors and marketers.
                                </p>
                                <ul class="list-unstyled text-start mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        List unlimited properties
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Property management tools
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Direct investor connections
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Analytics dashboard
                                    </li>
                                </ul>
                                <button type="button" class="btn btn-outline-primary w-100" onclick="selectMembership('owner')">
                                    Select Owner
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Investor -->
                    <div class="col-md-4">
                        <div class="card membership-card h-100 border-gold" onclick="selectMembership('investor')">
                            <div class="card-body text-center p-4">
                                <div class="membership-icon mb-4">
                                    <i class="bi bi-graph-up-arrow text-gold" style="font-size: 3.5rem;"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Investor</h4>
                                <span class="badge bg-gold text-navy mb-3">Most Popular</span>
                                <p class="text-muted mb-4">
                                    Invest in verified properties and earn returns. Access exclusive investment opportunities.
                                </p>
                                <ul class="list-unstyled text-start mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Browse verified properties
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Investment portfolio tracking
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        ROI analytics
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Secure transactions
                                    </li>
                                </ul>
                                <button type="button" class="btn btn-primary w-100" onclick="selectMembership('investor')">
                                    Select Investor
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Marketer -->
                    <div class="col-md-4">
                        <div class="card membership-card h-100" onclick="selectMembership('marketer')">
                            <div class="card-body text-center p-4">
                                <div class="membership-icon mb-4">
                                    <i class="bi bi-megaphone text-gold" style="font-size: 3.5rem;"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Marketer</h4>
                                <p class="text-muted mb-4">
                                    Promote properties and earn commissions. Build your real estate marketing business.
                                </p>
                                <ul class="list-unstyled text-start mb-4">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Access to property listings
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Commission tracking
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Marketing tools & materials
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Performance analytics
                                    </li>
                                </ul>
                                <button type="button" class="btn btn-outline-primary w-100" onclick="selectMembership('marketer')">
                                    Select Marketer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .membership-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .membership-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(228, 180, 0, 0.2);
        border-color: var(--gold);
    }

    .border-gold {
        border: 2px solid var(--gold) !important;
    }

    .membership-icon {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
    function selectMembership(type) {
        document.getElementById('membershipTypeInput').value = type;
        document.getElementById('membershipForm').submit();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/auth/select-membership.blade.php ENDPATH**/ ?>