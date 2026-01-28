

<?php $__env->startSection('title', 'Compliance Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Pending KYC</h6>
                        <h3 class="mb-0"><?php echo e($pendingKYC); ?></h3>
                    </div>
                    <i class="bi bi-file-earmark-person fs-1 opacity-50"></i>
                </div>
                <div class="mt-3">
                    <a href="<?php echo e(route('admin.kyc')); ?>" class="text-white text-decoration-none small">
                        Review Pending <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Approved KYC</h6>
                        <h3 class="mb-0"><?php echo e($approvedKYC); ?></h3>
                    </div>
                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Rejected KYC</h6>
                        <h3 class="mb-0"><?php echo e($rejectedKYC); ?></h3>
                    </div>
                    <i class="bi bi-x-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Recent KYC Submissions</h5>
        <a href="<?php echo e(route('admin.kyc')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">User</th>
                        <th>ID Type</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentKYC; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3 bg-navy text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 35px; height: 35px;">
                                    <?php echo e(substr($submission->user->name, 0, 1)); ?>

                                </div>
                                <div>
                                    <div class="fw-bold text-navy"><?php echo e($submission->user->name); ?></div>
                                    <div class="small text-muted"><?php echo e($submission->user->email); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($submission->getIdTypeLabel()); ?></td>
                        <td>
                            <?php if($submission->submitted_at): ?>
                                <?php echo e($submission->submitted_at->format('M d, Y')); ?>

                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?php echo e($submission->getStatusBadgeClass()); ?>">
                                <?php echo e($submission->getStatusLabel()); ?>

                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo e(route('admin.users.kyc', $submission->user_id)); ?>" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No recent submissions found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .text-navy { color: #0F1A3C; }
    .bg-navy { background-color: #0F1A3C; }
    .opacity-50 { opacity: 0.5; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/admin/dashboard/compliance_admin.blade.php ENDPATH**/ ?>