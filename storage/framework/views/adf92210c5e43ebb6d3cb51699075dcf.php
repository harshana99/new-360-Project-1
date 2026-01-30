

<?php $__env->startSection('title', 'My Properties'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Custom Dark Table Styling */
    .property-table {
        border-collapse: separate; 
        border-spacing: 0 10px; 
        width: 100%;
    }
    .property-table thead th {
        background-color: transparent !important;
        border: none;
        color: var(--text-muted);
        font-weight: 500;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 15px 10px 15px;
    }
    .property-table tbody tr {
        background-color: #1E293B !important; /* Slate 800 - Explicit Dark Color */
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .property-table tbody tr:hover {
        transform: translateY(-2px);
        background-color: #26334D !important; /* Lighter Slate for hover */
    }
    .property-table td {
        background-color: transparent !important; /* Inherit from TR */
        border: none;
        padding: 20px 15px;
        vertical-align: middle;
        color: white;
    }
    .property-table td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }
    .property-table td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }
    
    .prop-img-small {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        object-fit: cover;
        background-color: #334155;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: 0.2s;
        border: 1px solid rgba(255,255,255,0.1);
        color: var(--text-muted);
        background: transparent;
    }
    .action-btn:hover {
        background: rgba(255,255,255,0.1);
        color: white;
    }
    .action-btn.delete:hover {
        background: rgba(220, 38, 38, 0.2);
        color: #ef4444;
        border-color: #ef4444;
    }

    /* Tabs Styling override */
    .nav-tabs {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 30px;
    }
    .nav-tabs .nav-link {
        color: var(--text-muted);
        background: transparent;
        border: none;
        padding-bottom: 15px;
        position: relative;
    }
    .nav-tabs .nav-link:hover { color: white; }
    .nav-tabs .nav-link.active {
        color: var(--accent-gold);
        background: transparent;
        font-weight: 600;
    }
    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--accent-gold);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white fw-bold mb-0">My Properties</h2>
    <a href="<?php echo e(route('owner.properties.create')); ?>" class="btn" style="background: var(--accent-gold); color: #0F1A3C; font-weight: 600; border-radius: 10px; padding: 10px 20px;">
        <i class="bi bi-plus-lg me-2"></i>Add Property
    </a>
</div>

<!-- Custom Navigation Tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('owner.dashboard')); ?>">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="#">My Properties</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('owner.earnings')); ?>">Earnings</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('owner.documents')); ?>">Documents</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('owner.analytics')); ?>">Analytics</a>
    </li>
</ul>

<!-- Dark Table Container -->
<div class="stats-card p-4" style="min-height: 500px;">
    <?php if(session('success')): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10B981; color: #10B981;">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table property-table">
            <thead>
                <tr>
                    <th class="ps-3">Property</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Created</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="ps-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?php echo e($property->image_url ?? asset('images/placeholder.jpg')); ?>" 
                                 class="prop-img-small" 
                                 alt="Img"
                                 onerror="this.onerror=null;this.src='https://via.placeholder.com/50?text=No+Img';">
                            <div>
                                <h6 class="text-white mb-0 fw-bold"><?php echo e(Str::limit($property->title, 25)); ?></h6>
                                <small class="text-muted" style="font-size: 11px;">ID: #<?php echo e($property->id); ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-white"><?php echo e(Str::limit($property->location, 20)); ?></span>
                    </td>
                    <td>
                        <?php if($property->status == 'active'): ?>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10B981;">Active</span>
                        <?php elseif($property->status == 'pending'): ?>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #F59E0B;">Pending</span>
                        <?php elseif($property->status == 'rejected'): ?>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #EF4444;">Rejected</span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?php echo e(ucfirst($property->status)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold text-white">₦<?php echo e(number_format($property->price)); ?></td>
                    <td class="text-muted"><?php echo e($property->created_at->format('M d, Y')); ?></td>
                    <td class="text-end pe-3">
                        <a href="<?php echo e(route('properties.show', $property->id)); ?>" class="action-btn me-1" title="View Public Page"><i class="bi bi-eye"></i></a>
                        
                        <?php if($property->canBeEditedBy(auth()->id())): ?>
                            <a href="<?php echo e(route('owner.properties.edit', $property->id)); ?>" class="action-btn me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                        <?php endif; ?>
                        
                        <?php if($property->canBeDeletedBy(auth()->id())): ?>
                            <form action="<?php echo e(route('owner.properties.delete', $property->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this property permanently?');">
                                <?php echo csrf_field(); ?>
                                <button class="action-btn delete" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                                <i class="bi bi-building text-muted fs-2"></i>
                            </div>
                            <h5 class="text-white">No Properties Found</h5>
                            <p class="text-muted">Start by adding your first property listing.</p>
                            <a href="<?php echo e(route('owner.properties.create')); ?>" class="btn btn-sm mt-2" style="background: var(--accent-gold); color: #0F1A3C; font-weight: 600;">create One Now</a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Dark Pagination -->
    <div class="pt-4">
        <?php echo e($properties->onEachSide(1)->links('pagination::bootstrap-5')); ?> 
        <!-- Note: default pagination might be white. We might need custom styling for pagination later, but skipping for now to prioritize the list -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.dashboard-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/owner/properties/list.blade.php ENDPATH**/ ?>