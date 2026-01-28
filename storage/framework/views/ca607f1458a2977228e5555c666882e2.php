

<?php $__env->startSection('title', 'User Management - Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="bi bi-people-fill me-2"></i>User Management
                </h2>
                <a href="<?php echo e(route('admin.users.export', request()->query())); ?>" class="btn btn-success">
                    <i class="bi bi-download me-1"></i>Export to CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Users</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['total'])); ?></h3>
                        </div>
                        <i class="bi bi-people-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Approved</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['approved'])); ?></h3>
                        </div>
                        <i class="bi bi-check-circle-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending KYC</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['pending'])); ?></h3>
                        </div>
                        <i class="bi bi-clock-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Suspended</h6>
                            <h3 class="mb-0"><?php echo e(number_format($stats['suspended'])); ?></h3>
                        </div>
                        <i class="bi bi-slash-circle-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" value="<?php echo e($search); ?>" 
                           placeholder="Name, email, or phone...">
                </div>

                <!-- Membership Type -->
                <div class="col-md-3">
                    <label class="form-label">Membership Type</label>
                    <select class="form-select" name="membership_type">
                        <option value="">All Types</option>
                        <option value="owner" <?php echo e($membershipType === 'owner' ? 'selected' : ''); ?>>Owner</option>
                        <option value="investor" <?php echo e($membershipType === 'investor' ? 'selected' : ''); ?>>Investor</option>
                        <option value="marketer" <?php echo e($membershipType === 'marketer' ? 'selected' : ''); ?>>Marketer</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Statuses</option>
                        <option value="registered" <?php echo e($status === 'registered' ? 'selected' : ''); ?>>Registered</option>
                        <option value="membership_selected" <?php echo e($status === 'membership_selected' ? 'selected' : ''); ?>>Membership Selected</option>
                        <option value="kyc_submitted" <?php echo e($status === 'kyc_submitted' ? 'selected' : ''); ?>>KYC Submitted</option>
                        <option value="under_review" <?php echo e($status === 'under_review' ? 'selected' : ''); ?>>Under Review</option>
                        <option value="approved" <?php echo e($status === 'approved' ? 'selected' : ''); ?>>Approved</option>
                        <option value="rejected" <?php echo e($status === 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                        <option value="suspended" <?php echo e($status === 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-md-2 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Users (<?php echo e($users->total()); ?>)</h5>
            <div class="btn-group btn-group-sm">
                <a href="<?php echo e(route('admin.users.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_order' => 'desc']))); ?>" 
                   class="btn btn-outline-secondary <?php echo e($sortBy === 'created_at' && $sortOrder === 'desc' ? 'active' : ''); ?>">
                    Newest
                </a>
                <a href="<?php echo e(route('admin.users.index', array_merge(request()->query(), ['sort_by' => 'name', 'sort_order' => 'asc']))); ?>" 
                   class="btn btn-outline-secondary <?php echo e($sortBy === 'name' && $sortOrder === 'asc' ? 'active' : ''); ?>">
                    A-Z
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Membership</th>
                            <th>Status</th>
                            <th>KYC</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong>#<?php echo e($user->id); ?></strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?php echo e($user->name); ?></div>
                                            <small class="text-muted"><?php echo e($user->email); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        <i class="bi bi-award me-1"></i><?php echo e(ucfirst($user->membership_type)); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo e($user->getStatusBadgeClass()); ?>">
                                        <?php echo e($user->getStatusLabel()); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($user->hasKYC()): ?>
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                    <?php else: ?>
                                        <i class="bi bi-x-circle text-muted"></i>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo e($user->created_at->format('M d, Y')); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" 
                                           class="btn btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">No users found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($users->hasPages()): ?>
            <div class="card-footer">
                <?php echo e($users->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .stat-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #0F1A3C;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/admin/users/index.blade.php ENDPATH**/ ?>