<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketer Dashboard - 360WinEstate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-bg: #1e293b;
            --card-bg: #ffffff;
        }

        body {
            background: linear-gradient(135deg, #c026d3 0%, #9333ea 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            padding: 2rem 0;
        }

        .stats-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
            border: none;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .icon-primary { background: linear-gradient(135deg, #c026d3 0%, #9333ea 100%); color: white; }
        .icon-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .icon-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
        .icon-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
        .icon-info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0.5rem 0;
        }

        .stats-label {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-change {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .welcome-card {
            background: linear-gradient(135deg, #c026d3 0%, #9333ea 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(192, 38, 211, 0.3);
        }

        .welcome-card h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-card p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            background: white;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .action-btn:hover {
            border-color: #9333ea;
            background: #faf5ff;
            transform: translateX(5px);
        }

        .action-btn i {
            font-size: 1.5rem;
            color: #9333ea;
        }

        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .badge-role {
            background: linear-gradient(135deg, #c026d3 0%, #9333ea 100%);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .conversion-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .conversion-high {
            background: #d1fae5;
            color: #065f46;
        }

        .conversion-medium {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-megaphone"></i> 360WinEstate
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="badge badge-role">
                    <i class="bi bi-people"></i> Marketer
                </span>
                <span class="text-muted"><?php echo e($user->name); ?></span>
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container dashboard-container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h1><i class="bi bi-rocket-takeoff"></i> Welcome back, <?php echo e($user->name); ?>!</h1>
            <p>Your referral performance overview</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Total Referrals -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-primary">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <div class="stats-label">Total Referrals</div>
                    <div class="stats-value"><?php echo e($stats->total_referrals); ?></div>
                    <div class="stats-change text-success">
                        <i class="bi bi-arrow-up"></i> <?php echo e($stats->this_month_referrals); ?> This Month
                    </div>
                </div>
            </div>

            <!-- Verified Referrals -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stats-label">Verified Referrals</div>
                    <div class="stats-value"><?php echo e($stats->verified_referrals); ?></div>
                    <div class="stats-change text-muted">
                        <i class="bi bi-percent"></i> <?php echo e($stats->getVerificationRate()); ?>% Rate
                    </div>
                </div>
            </div>

            <!-- Converted Sales -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-info">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div class="stats-label">Converted Sales</div>
                    <div class="stats-value"><?php echo e($stats->converted_sales); ?></div>
                    <div class="stats-change">
                        <span class="conversion-badge conversion-high">
                            <i class="bi bi-arrow-up"></i> <?php echo e($stats->conversion_rate); ?>%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Commissions Earned -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-warning">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="stats-label">Commissions Earned</div>
                    <div class="stats-value"><?php echo e($stats->getFormattedCommissionsEarned()); ?></div>
                    <div class="stats-change text-success">
                        <i class="bi bi-arrow-up"></i> Total Earned
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="row">
            <!-- Team Size -->
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon icon-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stats-label">Team Size</div>
                    <div class="stats-value"><?php echo e($stats->team_size); ?></div>
                    <div class="stats-change text-muted">
                        <i class="bi bi-person"></i> Team Members
                    </div>
                </div>
            </div>

            <!-- This Month Commissions -->
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon icon-success">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stats-label">This Month</div>
                    <div class="stats-value"><?php echo e($stats->getFormattedThisMonthCommissions()); ?></div>
                    <div class="stats-change text-success">
                        <i class="bi bi-arrow-up"></i> Current Month
                    </div>
                </div>
            </div>

            <!-- Pending Commissions -->
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon icon-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stats-label">Pending</div>
                    <div class="stats-value"><?php echo e($stats->getFormattedPendingCommissions()); ?></div>
                    <div class="stats-change text-warning">
                        <i class="bi bi-clock"></i> <?php echo e($stats->pending_referrals); ?> Referrals
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="quick-actions">
                    <h4 class="mb-3"><i class="bi bi-graph-up"></i> Performance Metrics</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <div class="text-muted mb-2">Conversion Rate</div>
                                <div class="display-6 fw-bold text-success"><?php echo e($stats->conversion_rate); ?>%</div>
                                <small class="text-muted"><?php echo e($stats->converted_sales); ?>/<?php echo e($stats->verified_referrals); ?> converted</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <div class="text-muted mb-2">Verification Rate</div>
                                <div class="display-6 fw-bold text-info"><?php echo e($stats->getVerificationRate()); ?>%</div>
                                <small class="text-muted"><?php echo e($stats->verified_referrals); ?>/<?php echo e($stats->total_referrals); ?> verified</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3">
                                <div class="text-muted mb-2">Avg. Commission/Sale</div>
                                <div class="display-6 fw-bold text-warning">₹<?php echo e(number_format($stats->getAverageCommissionPerSale(), 0)); ?></div>
                                <small class="text-muted">Per successful sale</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="quick-actions">
                    <h4 class="mb-3"><i class="bi bi-lightning-charge"></i> Quick Actions</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <a href="#" class="action-btn">
                                <i class="bi bi-person-plus-fill"></i>
                                <div>
                                    <strong>Add Referral</strong>
                                    <div class="text-muted small">New referral entry</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="action-btn">
                                <i class="bi bi-people"></i>
                                <div>
                                    <strong>View Team</strong>
                                    <div class="text-muted small">Team performance</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="action-btn">
                                <i class="bi bi-cash-stack"></i>
                                <div>
                                    <strong>Request Payout</strong>
                                    <div class="text-muted small">Withdraw commissions</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/dashboard/marketer.blade.php ENDPATH**/ ?>