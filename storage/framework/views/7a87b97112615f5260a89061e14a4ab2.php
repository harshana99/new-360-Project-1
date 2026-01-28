

<?php $__env->startSection('title', 'KYC Details - ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="<?php echo e(route('admin.users.show', $user->id)); ?>" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to User Details
            </a>
            <h2 class="mb-0">KYC Document Review</h2>
            <p class="text-muted"><?php echo e($user->name); ?> (ID: #<?php echo e($user->id); ?>)</p>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Current KYC -->
        <div class="col-lg-8 mb-4">
            <!-- KYC Status Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-check me-2"></i>Current KYC Submission
                    </h5>
                    <span class="badge bg-<?php echo e($kycSubmission->status === 'approved' ? 'success' : ($kycSubmission->status === 'rejected' ? 'danger' : 'warning')); ?> fs-6">
                        <?php echo e(ucfirst(str_replace('_', ' ', $kycSubmission->status))); ?>

                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Submission ID</label>
                            <p class="mb-0 fw-semibold">#<?php echo e($kycSubmission->id); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Submitted On</label>
                            <p class="mb-0 fw-semibold"><?php echo e($kycSubmission->submitted_at->format('M d, Y h:i A')); ?></p>
                        </div>
                        <?php if($kycSubmission->reviewed_at): ?>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Reviewed On</label>
                                <p class="mb-0 fw-semibold"><?php echo e($kycSubmission->reviewed_at->format('M d, Y h:i A')); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Reviewed By</label>
                                <p class="mb-0 fw-semibold">
                                    <?php if($kycSubmission->reviewed_by_admin_id): ?>
                                        Admin #<?php echo e($kycSubmission->reviewed_by_admin_id); ?>

                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Full Name (as on ID)</label>
                            <p class="mb-0 fw-semibold"><?php echo e($kycSubmission->full_name); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Date of Birth</label>
                            <p class="mb-0 fw-semibold">
                                <?php echo e(\Carbon\Carbon::parse($kycSubmission->date_of_birth)->format('M d, Y')); ?>

                                <small class="text-muted">(<?php echo e(\Carbon\Carbon::parse($kycSubmission->date_of_birth)->age); ?> years old)</small>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">ID Type</label>
                            <p class="mb-0 fw-semibold"><?php echo e(ucfirst(str_replace('_', ' ', $kycSubmission->id_type))); ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">ID Number</label>
                            <p class="mb-0 fw-semibold"><?php echo e($kycSubmission->id_number); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Address Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><?php echo e($kycSubmission->address); ?></p>
                    <p class="mb-0">
                        <?php echo e($kycSubmission->city); ?>, <?php echo e($kycSubmission->state); ?> <?php echo e($kycSubmission->postal_code); ?><br>
                        <?php echo e($kycSubmission->country); ?>

                    </p>
                </div>
            </div>

            <!-- Documents -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-image me-2"></i>Submitted Documents</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- ID Document -->
                        <div class="col-md-6 mb-3">
                            <h6>ID Document</h6>
                            <?php if($kycSubmission->id_image_path): ?>
                                <div class="document-preview mb-2">
                                    <img src="<?php echo e(asset('storage/' . $kycSubmission->id_image_path)); ?>" 
                                         alt="ID Document" 
                                         class="img-fluid rounded border"
                                         style="max-height: 300px; cursor: pointer;"
                                         onclick="window.open(this.src, '_blank')">
                                </div>
                                <a href="<?php echo e(asset('storage/' . $kycSubmission->id_image_path)); ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   download>
                                    <i class="bi bi-download me-1"></i>Download
                                </a>
                            <?php else: ?>
                                <p class="text-muted">No document uploaded</p>
                            <?php endif; ?>
                        </div>

                        <!-- Address Proof -->
                        <div class="col-md-6 mb-3">
                            <h6>Address Proof</h6>
                            <?php if($kycSubmission->address_proof_path): ?>
                                <div class="document-preview mb-2">
                                    <?php if(str_ends_with($kycSubmission->address_proof_path, '.pdf')): ?>
                                        <div class="pdf-placeholder border rounded p-4 text-center bg-light">
                                            <i class="bi bi-file-pdf text-danger" style="font-size: 4rem;"></i>
                                            <p class="mt-2 mb-0">PDF Document</p>
                                        </div>
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('storage/' . $kycSubmission->address_proof_path)); ?>" 
                                             alt="Address Proof" 
                                             class="img-fluid rounded border"
                                             style="max-height: 300px; cursor: pointer;"
                                             onclick="window.open(this.src, '_blank')">
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo e(asset('storage/' . $kycSubmission->address_proof_path)); ?>" 
                                   class="btn btn-sm btn-outline-primary" 
                                   download>
                                    <i class="bi bi-download me-1"></i>Download
                                </a>
                            <?php else: ?>
                                <p class="text-muted">No document uploaded</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Feedback -->
            <?php if($kycSubmission->rejection_reason || $kycSubmission->admin_notes): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Admin Feedback</h5>
                    </div>
                    <div class="card-body">
                        <?php if($kycSubmission->rejection_reason): ?>
                            <div class="mb-3">
                                <label class="text-muted small">Rejection Reason</label>
                                <div class="alert alert-danger mb-0">
                                    <?php echo e($kycSubmission->rejection_reason); ?>

                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($kycSubmission->admin_notes): ?>
                            <div>
                                <label class="text-muted small">Admin Notes</label>
                                <p class="mb-0"><?php echo e($kycSubmission->admin_notes); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column - Actions & History -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <?php if($kycSubmission->status === 'submitted' || $kycSubmission->status === 'under_review'): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Review Actions</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Review the documents and take action:</p>
                        
                        <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="bi bi-check-circle me-1"></i>Approve KYC
                        </button>
                        
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-1"></i>Reject KYC
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- KYC History -->
            <?php if($kycHistory && count($kycHistory) > 1): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Submission History</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $kycHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kyc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item <?php echo e($kyc->id === $kycSubmission->id ? 'active' : ''); ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">Submission #<?php echo e($kyc->id); ?></h6>
                                        <small><?php echo e($kyc->submitted_at->format('M d, Y')); ?></small>
                                    </div>
                                    <span class="badge bg-<?php echo e($kyc->status === 'approved' ? 'success' : ($kyc->status === 'rejected' ? 'danger' : 'warning')); ?>">
                                        <?php echo e(ucfirst($kyc->status)); ?>

                                    </span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.kyc.approve', $kycSubmission->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Approve KYC</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to approve the KYC submission for <strong><?php echo e($user->name); ?></strong>.</p>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="3" 
                                  placeholder="Any additional notes..."></textarea>
                    </div>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        The user will be notified via email and their account will be activated.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve KYC</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.kyc.reject', $kycSubmission->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject KYC</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>You are about to reject the KYC submission for <strong><?php echo e($user->name); ?></strong>.</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">
                            Rejection Reason <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" 
                                  required placeholder="Explain why the KYC is being rejected..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="admin_notes_reject" class="form-label">Admin Notes (Optional)</label>
                        <textarea class="form-control" id="admin_notes_reject" name="admin_notes" rows="2" 
                                  placeholder="Internal notes..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        The user will be notified and can resubmit their KYC documents.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject KYC</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .document-preview img {
        transition: transform 0.2s;
    }
    
    .document-preview img:hover {
        transform: scale(1.02);
    }
    
    .pdf-placeholder {
        height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/admin/users/kyc-details.blade.php ENDPATH**/ ?>