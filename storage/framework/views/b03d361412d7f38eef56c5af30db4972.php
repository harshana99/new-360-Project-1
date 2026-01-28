<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Status - 360WinEstate</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0F1A3C 0%, #1a2847 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: #0F1A3C !important;
        }
        
        .navbar-brand .gold {
            color: #E4B400;
        }
        
        .btn-logout {
            background: #E4B400;
            color: #0F1A3C;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: #d4a400;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(228, 180, 0, 0.3);
        }
        
        .main-container {
            max-width: 800px;
            margin: 40px auto;
        }
        
        .alert-custom {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .alert-custom i {
            font-size: 20px;
        }
        
        .status-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .status-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #E4B400 0%, #f5c842 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
        }
        
        .status-badge {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 10px 30px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .status-title {
            font-size: 28px;
            font-weight: 700;
            color: #0F1A3C;
            margin-bottom: 15px;
        }
        
        .status-description {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .next-steps {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 20px;
            border-radius: 8px;
            text-align: left;
            margin-bottom: 30px;
        }
        
        .next-steps h6 {
            font-weight: 700;
            color: #0F1A3C;
            margin-bottom: 15px;
        }
        
        .next-steps ol {
            margin: 0;
            padding-left: 20px;
        }
        
        .next-steps li {
            margin-bottom: 8px;
            color: #495057;
        }
        
        .btn-kyc {
            background: linear-gradient(135deg, #E4B400 0%, #f5c842 100%);
            color: #0F1A3C;
            border: none;
            padding: 15px 40px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-kyc:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(228, 180, 0, 0.4);
            color: #0F1A3C;
        }
        
        .info-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
        }
        
        .info-card h5 {
            font-weight: 700;
            color: #0F1A3C;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            text-align: left;
        }
        
        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #0F1A3C;
        }
        
        .verified-badge {
            color: #28a745;
            font-size: 18px;
            margin-left: 5px;
        }
        
        .help-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #dee2e6;
        }
        
        .help-section a {
            color: #E4B400;
            text-decoration: none;
            font-weight: 600;
        }
        
        .help-section a:hover {
            text-decoration: underline;
        }
        
        /* Progress Tracker - NOT SHOWN YET, will add in next update */
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                360<span class="gold">Win</span>Estate
            </a>
            <div class="d-flex align-items-center">
                <span class="me-3 text-muted"><?php echo e($user->name); ?></span>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-logout">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container" style="margin-top: 80px;">
        <!-- Success/Info Messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-custom alert-success">
                <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="alert alert-custom alert-info">
                <i class="bi bi-info-circle-fill me-2"></i><?php echo e(session('info')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
            <div class="alert alert-custom alert-warning">
                <i class="bi bi-exclamation-circle-fill me-2"></i><?php echo e(session('warning')); ?>

            </div>
        <?php endif; ?>

        <!-- Status Card -->
        <div class="status-card text-center">
            <!-- Status Icon -->
            <div class="status-icon">
                <?php if($user->status === 'rejected'): ?>
                    <i class="bi bi-x-circle-fill"></i>
                <?php elseif($user->status === 'kyc_submitted' || $user->status === 'under_review'): ?>
                    <i class="bi bi-clock-history"></i>
                <?php else: ?>
                    <i class="bi bi-hourglass-split"></i>
                <?php endif; ?>
            </div>

            <!-- Status Badge -->
            <div class="status-badge">
                <?php if($user->status === 'membership_selected'): ?>
                    Membership Selected
                <?php elseif($user->status === 'kyc_submitted'): ?>
                    KYC Submitted
                <?php elseif($user->status === 'under_review'): ?>
                    Under Review
                <?php elseif($user->status === 'rejected'): ?>
                    Rejected
                <?php else: ?>
                    Pending
                <?php endif; ?>
            </div>

            <!-- Status Title & Description -->
            <?php if($user->status === 'membership_selected'): ?>
                <h2 class="status-title">Complete Your KYC</h2>
                <p class="status-description">
                    You've selected <strong><?php echo e(ucfirst($user->membership_type)); ?></strong> membership. 
                    Please complete your KYC verification to access the dashboard.
                </p>

                <!-- Next Steps -->
                <div class="next-steps">
                    <h6><i class="bi bi-list-check me-2"></i>Next Steps:</h6>
                    <ol>
                        <li>Prepare your identification documents</li>
                        <li>Complete the KYC verification form</li>
                        <li>Wait for admin approval (24-48 hours)</li>
                    </ol>
                </div>

                <!-- CTA Button -->
                <a href="<?php echo e(route('kyc.create')); ?>" class="btn-kyc">
                    <i class="bi bi-file-earmark-text me-2"></i>Start KYC Verification
                </a>

            <?php elseif($user->status === 'kyc_submitted'): ?>
                <h2 class="status-title">KYC Submitted</h2>
                <p class="status-description">
                    Thank you for submitting your KYC documents. Our team will review them shortly.
                </p>
                <div class="next-steps">
                    <h6><i class="bi bi-info-circle me-2"></i>What's Next:</h6>
                    <ol>
                        <li>Our team is reviewing your documents</li>
                        <li>You'll receive an email notification once reviewed</li>
                        <li>Typical review time: 24-48 hours</li>
                    </ol>
                </div>
                <a href="<?php echo e(route('kyc.status')); ?>" class="btn-kyc">
                    <i class="bi bi-eye me-2"></i>View KYC Status
                </a>

            <?php elseif($user->status === 'under_review'): ?>
                <h2 class="status-title">Under Review</h2>
                <p class="status-description">
                    Your account is currently being reviewed by our team. This process typically takes 24-48 hours.
                </p>

            <?php elseif($user->status === 'rejected'): ?>
                <h2 class="status-title text-danger">Account Rejected</h2>
                <p class="status-description">
                    Unfortunately, your account application has been rejected.
                </p>
                <?php if($user->rejection_reason): ?>
                    <div class="alert alert-danger text-start">
                        <strong>Reason:</strong><br>
                        <?php echo e($user->rejection_reason); ?>

                    </div>
                <?php endif; ?>
                <a href="<?php echo e(route('kyc.resubmit')); ?>" class="btn-kyc">
                    <i class="bi bi-arrow-repeat me-2"></i>Resubmit KYC
                </a>

            <?php else: ?>
                <h2 class="status-title">Account Pending</h2>
                <p class="status-description">
                    Your account setup is incomplete. Please complete all required steps.
                </p>
            <?php endif; ?>

            <!-- User Information -->
            <div class="info-card">
                <h5>Your Information</h5>
                <div class="info-row">
                    <div class="info-item">
                        <div class="info-label">Name</div>
                        <div class="info-value"><?php echo e($user->name); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <?php echo e($user->email); ?>

                            <?php if($user->hasVerifiedEmail()): ?>
                                <i class="bi bi-check-circle-fill verified-badge"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if($user->phone): ?>
                        <div class="info-item">
                            <div class="info-label">Phone</div>
                            <div class="info-value"><?php echo e($user->phone); ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if($user->membership_type): ?>
                        <div class="info-item">
                            <div class="info-label">Membership Type</div>
                            <div class="info-value"><?php echo e(ucfirst($user->membership_type)); ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="info-item">
                        <div class="info-label">Registered On</div>
                        <div class="info-value"><?php echo e($user->created_at->format('M d, Y')); ?></div>
                    </div>
                    <?php if($user->membership_type): ?>
                        <div class="info-item">
                            <div class="info-label">Membership Selected</div>
                            <div class="info-value"><?php echo e($user->updated_at->format('M d, Y')); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Help Section -->
            <div class="help-section">
                <p class="text-muted mb-2">
                    <i class="bi bi-question-circle me-2"></i>Need help?
                </p>
                <a href="mailto:support@360winestate.com">Contact Support</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/dashboard/locked.blade.php ENDPATH**/ ?>