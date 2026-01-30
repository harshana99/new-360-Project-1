<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - 360WinEstate</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts: Outfit (Premium Look) -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-dark: #0B1221;
            --bg-card: #151C2F;
            --text-main: #FFFFFF;
            --text-muted: #adb5bd;
            --accent-gold: #F59E0B;
            --accent-green: #10B981;
        }

        .text-muted { color: var(--text-muted) !important; }

        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--bg-dark); 
            color: var(--text-main);
            overflow-x: hidden;
        }
        
        /* GLOWING GLASS EFFECT */
        body {
            background: radial-gradient(circle at top right, #1a233a 0%, #0B1221 60%), #0B1221;
        }

        .glass-card, .stats-card, .property-list-item {
            background: rgba(21, 28, 47, 0.6);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .glass-card:hover, .stats-card:hover, .property-list-item:hover {
            box-shadow: 0 0 25px rgba(245, 158, 11, 0.15), inset 0 0 0 1px rgba(255, 255, 255, 0.1);
            border-color: rgba(245, 158, 11, 0.3);
            transform: translateY(-3px);
        }

        /* SIDEBAR */
        .sidebar {
            background: var(--bg-dark);
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            border-right: 1px solid rgba(255,255,255,0.05);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .sidebar .logo {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            padding-left: 10px;
        }
        .sidebar .logo i { color: var(--accent-gold); font-size: 24px; }
        
        .sidebar-menu { list-style: none; padding: 0; }
        .sidebar-menu li { margin-bottom: 8px; }
        
        .sidebar a {
            color: var(--text-muted);
            text-decoration: none;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 400;
            font-size: 15px;
        }
        .sidebar a:hover, .sidebar a.active {
            color: white;
            background: rgba(255,255,255,0.08);
        }
        .sidebar a.active { font-weight: 500; }
        
        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            padding: 30px 40px;
            min-height: 100vh;
        }
        
        /* HEADER */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .welcome-text h2 { font-size: 24px; font-weight: 600; margin: 0; }
        .welcome-text span { font-size: 24px; }
        
        .header-actions { display: flex; align-items: center; gap: 20px; }
        .icon-btn { 
            background: transparent; border: none; color: white; font-size: 20px; position: relative; 
        }
        .profile-pic {
            width: 40px; height: 40px; border-radius: 50%; background: #334155;
            display: flex; align-items: center; justify-content: center; font-weight: 600;
        }
        
        /* CARDS - Glass Stats Card uses above definition for bg/border */
        .stats-card {
            /* background & border handled by glass css above */
            border-radius: 16px;
            padding: 25px;
            height: 100%;
        }
        .stats-card h3 { font-size: 14px; color: white; font-weight: 500; margin-bottom: 10px; letter-spacing: 0.5px; opacity: 0.9; }
        .stats-card .value { font-size: 28px; font-weight: 700; color: white; margin-bottom: 5px; }
        .stats-card .subtext { font-size: 13px; color: var(--text-muted); }
        
        /* BUTTONS */
        .btn-action {
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
            color: white;
            font-weight: 500;
            transition: 0.3s;
        }
        .btn-action:hover { background: rgba(255,255,255,0.1); }
        
        /* TABLE / LIST */
        .property-list-item {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            border: 1px solid rgba(255,255,255,0.03);
        }
        .prop-info { display: flex; align-items: center; gap: 15px; }
        .prop-img { width: 60px; height: 60px; border-radius: 10px; object-fit: cover; background: #333; }
        .prop-text h4 { font-size: 16px; margin: 0; font-weight: 600; }
        .prop-text p { font-size: 13px; color: var(--text-muted); margin: 0; }
        .prop-price { text-align: right; }
        .prop-price h4 { font-size: 16px; margin: 0; font-weight: 600; }
        .prop-price span { font-size: 12px; color: var(--accent-green); }

        /* RESPONSIVE */
        .hamburger { display: none; color: white; background: none; border: none; font-size: 24px; }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); z-index: 1050; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .hamburger { display: block; }
            
            /* Sidebar Backdrop */
            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0; left: 0; width: 100vw; height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
                backdrop-filter: blur(2px);
            }
            .sidebar-backdrop.active { display: block; }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <i class="bi bi-building"></i>
            360WinEstate
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->routeIs('dashboard*') || request()->routeIs('*.dashboard') ? 'active' : ''); ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a></li>

            <?php if(auth()->user()->isOwner()): ?>
                <li><a href="<?php echo e(route('owner.properties')); ?>" class="<?php echo e(request()->routeIs('owner.properties*') ? 'active' : ''); ?>">
                    <i class="bi bi-houses-fill"></i> My Properties
                </a></li>
                <li><a href="<?php echo e(route('owner.earnings')); ?>" class="<?php echo e(request()->routeIs('owner.earnings*') ? 'active' : ''); ?>">
                    <i class="bi bi-cash-stack"></i> Earnings
                </a></li>
                <li><a href="<?php echo e(route('owner.documents')); ?>" class="<?php echo e(request()->routeIs('owner.documents*') ? 'active' : ''); ?>">
                    <i class="bi bi-file-earmark-text"></i> Documents
                </a></li>
                <li><a href="<?php echo e(route('owner.analytics')); ?>" class="<?php echo e(request()->routeIs('owner.analytics*') ? 'active' : ''); ?>">
                    <i class="bi bi-graph-up"></i> Analytics
                </a></li>
            <?php endif; ?>

            <?php if(auth()->user()->isMarketer()): ?>
                <li><a href="<?php echo e(route('marketer.team')); ?>" class="<?php echo e(request()->routeIs('marketer.team*') ? 'active' : ''); ?>">
                    <i class="bi bi-people-fill"></i> My Team
                </a></li>
                <li><a href="<?php echo e(route('marketer.commissions')); ?>" class="<?php echo e(request()->routeIs('marketer.commissions*') ? 'active' : ''); ?>">
                    <i class="bi bi-wallet-fill"></i> Commissions
                </a></li>
                <li><a href="<?php echo e(route('marketer.leaderboard')); ?>" class="<?php echo e(request()->routeIs('marketer.leaderboard*') ? 'active' : ''); ?>">
                    <i class="bi bi-trophy-fill"></i> Leaderboard
                </a></li>
                <li><a href="<?php echo e(route('marketer.targets')); ?>" class="<?php echo e(request()->routeIs('marketer.targets*') ? 'active' : ''); ?>">
                    <i class="bi bi-bullseye"></i> Targets
                </a></li>
            <?php endif; ?>

            <?php if(auth()->user()->isInvestor()): ?>
                <li><a href="<?php echo e(route('investor.portfolio')); ?>" class="<?php echo e(request()->routeIs('investor.portfolio*') ? 'active' : ''); ?>">
                    <i class="bi bi-briefcase-fill"></i> Portfolio
                </a></li>
                <li><a href="<?php echo e(route('investor.investments')); ?>" class="<?php echo e(request()->routeIs('investor.investments*') ? 'active' : ''); ?>">
                    <i class="bi bi-graph-up-arrow"></i> Investments
                </a></li>
                <li><a href="<?php echo e(route('investor.dividends')); ?>" class="<?php echo e(request()->routeIs('investor.dividends*') ? 'active' : ''); ?>">
                    <i class="bi bi-piggy-bank-fill"></i> Dividends
                </a></li>
            <?php endif; ?>
        </ul>

        <div style="position: absolute; bottom: 30px; left: 20px; width: calc(100% - 40px);">
             <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" style="background:none; border:none; color: var(--text-muted); display: flex; gap: 10px; align-items: center; padding: 10px;">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="d-flex align-items-center gap-3">
                <button class="hamburger" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <div class="welcome-text">
                    <h2>Welcome, <?php echo e(explode(' ', auth()->user()->name)[0]); ?> <span>👋</span></h2>
                </div>
            </div>
            
            <div class="header-actions">
                <button class="icon-btn"><i class="bi bi-bell"></i></button>
                <div class="profile-pic">
                    <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" style="background: rgba(16, 185, 129, 0.2); color: #10B981; border: 1px solid #10B981;">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.sidebar-backdrop').classList.toggle('active');
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/dashboards/layouts/dashboard-layout.blade.php ENDPATH**/ ?>