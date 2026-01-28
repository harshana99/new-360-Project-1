<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - 360WinEstate</title>
    
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
            background: #f8f9fa;
        }
        
        .sidebar {
            background: linear-gradient(135deg, #0F1A3C 0%, #1a2847 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            width: 250px;
            padding: 20px 0;
            transition: transform 0.3s ease;
            z-index: 1000;
            left: 0;
            top: 0;
        }
        
        .sidebar .logo {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
            color: white;
        }
        
        .sidebar .logo .gold {
            color: #E4B400;
        }
        
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s ease;
        }
        
        .sidebar a:hover {
            background: #E4B400;
            color: #0F1A3C;
            padding-left: 30px;
        }
        
        .sidebar a.active {
            background: #E4B400;
            color: #0F1A3C;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 30px;
            min-height: 100vh;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }
        
        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 36px;
            font-weight: 700;
            color: #0F1A3C;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #6c757d;
            margin: 0;
        }
        
        .stat-card .icon {
            font-size: 40px;
            color: #E4B400;
            float: right;
        }
        
        .btn-gold {
            background: linear-gradient(135deg, #E4B400 0%, #f5c842 100%);
            color: #0F1A3C;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(228, 180, 0, 0.3);
            color: #0F1A3C;
        }
        
        .badge-super {
            background: #dc3545;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        /* Hamburger Menu */
        .hamburger {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            background: #0F1A3C;
            border: none;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .hamburger span {
            display: block;
            width: 25px;
            height: 3px;
            background: #E4B400;
            margin: 5px 0;
            transition: 0.3s;
        }
        
        .hamburger.active span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }
        
        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }
        
        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        /* Mobile Styles */
        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 80px 15px 15px 15px;
            }
            
            .header {
                padding: 15px 20px;
            }
            
            .header h2 {
                font-size: 20px;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-card h3 {
                font-size: 28px;
            }
            
            .stat-card .icon {
                font-size: 30px;
            }
            
            .btn-gold {
                padding: 10px 20px;
                font-size: 14px;
                width: 100%;
                margin-bottom: 10px;
            }
            
            .card-body .btn-gold,
            .card-body .btn-outline-secondary {
                margin-right: 0 !important;
            }
        }
        
        /* Tablet Styles */
        @media (min-width: 769px) and (max-width: 1024px) {
            .sidebar {
                width: 220px;
            }
            
            .main-content {
                margin-left: 220px;
                width: calc(100% - 220px);
                padding: 20px;
            }
            
            .stat-card h3 {
                font-size: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Hamburger Menu Button (Top-Right) -->
    <button class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            360<span class="gold">Win</span>Estate
        </div>
        <hr style="border-color: rgba(255,255,255,0.2);">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="active">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="<?php echo e(route('admin.admins')); ?>">
            <i class="bi bi-people me-2"></i> Manage Admins
        </a>
        <a href="<?php echo e(route('admin.users')); ?>">
            <i class="bi bi-person me-2"></i> Users
        </a>
        <a href="<?php echo e(route('admin.kyc')); ?>">
            <i class="bi bi-file-check me-2"></i> KYC Management
        </a>
        <a href="<?php echo e(route('admin.analytics')); ?>">
            <i class="bi bi-graph-up me-2"></i> Analytics
        </a>
        <hr style="border-color: rgba(255,255,255,0.2);">
        <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start" style="padding: 12px 20px;">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h2 class="mb-0">Super Admin Dashboard</h2>
            <p class="mb-0">Welcome, <strong><?php echo e($user->name); ?></strong>! <span class="badge-super">Super Admin</span></p>
        </div>

        <!-- Success Messages -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <i class="bi bi-people icon"></i>
                                <h3><?php echo e($totalUsers); ?></h3>
                                <p>Total Users</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <i class="bi bi-check-circle icon"></i>
                                <h3><?php echo e($approvedUsers); ?></h3>
                                <p>Approved Users</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <i class="bi bi-shield-check icon"></i>
                                <h3><?php echo e($activeAdmins); ?></h3>
                                <p>Active Admins</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-4">
                        <div class="card-header" style="background: #E4B400; color: #0F1A3C;">
                            <h5 class="mb-0" style="font-weight: 600;">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="<?php echo e(route('admin.create')); ?>" class="btn btn-gold me-2">
                                <i class="bi bi-plus-circle me-2"></i>Create New Admin
                            </a>
                            <a href="<?php echo e(route('admin.admins')); ?>" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-people me-2"></i>Manage Admins
                            </a>
                            <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-person me-2"></i>View All Users
                            </a>
                        </div>
                    </div>

                    <!-- Pending Items -->
                    <div class="card mt-4">
                        <div class="card-header" style="background: #E4B400; color: #0F1A3C;">
                            <h5 class="mb-0" style="font-weight: 600;">Pending Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-text text-warning me-3" style="font-size: 30px;"></i>
                                        <div>
                                            <h4 class="mb-0"><?php echo e($pendingKYC); ?></h4>
                                            <small class="text-muted">Pending KYC</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Admins -->
                    <?php if($recentAdmins->count() > 0): ?>
                    <div class="card mt-4">
                        <div class="card-header" style="background: #E4B400; color: #0F1A3C;">
                            <h5 class="mb-0" style="font-weight: 600;">Recent Admins</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $recentAdmins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentAdmin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($recentAdmin->user->name); ?></td>
                                            <td><span class="badge <?php echo e($recentAdmin->getRoleBadgeClass()); ?>"><?php echo e($recentAdmin->getRoleLabel()); ?></span></td>
                                            <td><span class="badge <?php echo e($recentAdmin->getStatusBadgeClass()); ?>"><?php echo e(ucfirst($recentAdmin->status)); ?></span></td>
                                            <td><?php echo e($recentAdmin->created_at->diffForHumans()); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Menu Toggle Script -->
    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        // Toggle sidebar
        function toggleSidebar() {
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        // Hamburger click (toggle open/close)
        hamburger.addEventListener('click', toggleSidebar);
        
        // Overlay click (close sidebar)
        overlay.addEventListener('click', toggleSidebar);
        
        // Close sidebar when clicking a link (mobile only)
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                hamburger.classList.remove('active');
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp1\htdocs\new 360 Project\resources\views/admin/dashboard/super_admin.blade.php ENDPATH**/ ?>