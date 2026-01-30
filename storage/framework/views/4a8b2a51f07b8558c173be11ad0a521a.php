

<?php $__env->startSection('title', 'Documents'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Tabs Styling */
    .nav-tabs { border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 30px; }
    .nav-tabs .nav-link { color: var(--text-muted); background: transparent; border: none; padding-bottom: 15px; position: relative; }
    .nav-tabs .nav-link:hover { color: white; }
    .nav-tabs .nav-link.active { color: var(--accent-gold); background: transparent; font-weight: 600; }
    .nav-tabs .nav-link.active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: var(--accent-gold); }

    .doc-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 20px;
        transition: 0.2s;
        display: flex;
        align-items: center;
    }
    .doc-card:hover {
        background: rgba(255,255,255,0.06);
        transform: translateY(-2px);
        border-color: rgba(255,255,255,0.1);
    }
    .doc-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.05);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 24px;
        color: var(--accent-gold);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="fw-bold text-white mb-4">My Documents</h2>

<!-- Custom Navigation Tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('owner.dashboard')); ?>">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('owner.properties')); ?>">My Properties</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('owner.earnings')); ?>">Earnings</a></li>
    <li class="nav-item"><a class="nav-link active" href="#">Documents</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('owner.analytics')); ?>">Analytics</a></li>
</ul>

<div class="stats-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold text-white mb-0">Recent Files</h5>
        <div class="input-group" style="width: 250px;">
            <input type="text" class="form-control" placeholder="Search files..." style="background: rgba(255,255,255,0.05); border: none; color: white;">
            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
        </div>
    </div>

    <div class="row g-3">
        <!-- Document Items -->
        <div class="col-md-6 col-lg-4">
            <div class="doc-card">
                <div class="doc-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                <div class="flex-grow-1">
                    <h6 class="text-white mb-1">Ownership_Deed.pdf</h6>
                    <small class="text-muted">2.4 MB • Jan 15, 2026</small>
                </div>
                <button class="btn btn-sm btn-link text-white"><i class="bi bi-download"></i></button>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="doc-card">
                <div class="doc-icon"><i class="bi bi-file-earmark-image"></i></div>
                <div class="flex-grow-1">
                    <h6 class="text-white mb-1">Property_Photos.zip</h6>
                    <small class="text-muted">15 MB • Jan 12, 2026</small>
                </div>
                <button class="btn btn-sm btn-link text-white"><i class="bi bi-download"></i></button>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="doc-card">
                <div class="doc-icon"><i class="bi bi-file-earmark-text"></i></div>
                <div class="flex-grow-1">
                    <h6 class="text-white mb-1">Contract_Agreement.docx</h6>
                    <small class="text-muted">800 KB • Dec 28, 2025</small>
                </div>
                <button class="btn btn-sm btn-link text-white"><i class="bi bi-download"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="alert mt-4 d-flex align-items-center" role="alert" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); color: #60a5fa;">
    <i class="bi bi-info-circle me-3 fs-5"></i>
    <div>
        <strong>Need to upload new documents?</strong> Please go to the Property details page to upload specific documents for approval.
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboards.layouts.dashboard-layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/dashboards/owner/documents.blade.php ENDPATH**/ ?>