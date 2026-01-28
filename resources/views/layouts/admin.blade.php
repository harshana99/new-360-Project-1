<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - 360WinEstate</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; }
        
        /* Sidebar */
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
        .sidebar .logo .gold { color: #E4B400; }
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
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            min-height: 100vh;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }
        
        /* Header */
        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Mobile */
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
        .hamburger.active span:nth-child(1) { transform: rotate(-45deg) translate(-5px, 6px); }
        .hamburger.active span:nth-child(2) { opacity: 0; }
        .hamburger.active span:nth-child(3) { transform: rotate(45deg) translate(-5px, -6px); }
        
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
        
        @media (max-width: 768px) {
            .hamburger { display: block; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .sidebar-overlay.active { display: block; }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 80px 15px 15px 15px;
            }
        }

        /* Utility */
        .btn-gold { 
            background: linear-gradient(135deg, #E4B400 0%, #f5c842 100%);
            color: #0F1A3C;
            border: none;
        }
    </style>
</head>
<body>
    <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">360<span class="gold">Win</span>Estate</div>
        <hr style="border-color: rgba(255,255,255,0.2);">
        
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        
        @if(auth()->user()->admin->isSuperAdmin())
        <a href="{{ route('admin.admins') }}" class="{{ request()->routeIs('admin.admins*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Manage Admins
        </a>
        @endif

        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="bi bi-person me-2"></i> Users
        </a>
        
        {{-- Show KYC link if route exists and user has permission --}}
        @if(Route::has('admin.kyc') && (auth()->user()->admin->isSuperAdmin() || auth()->user()->admin->isComplianceAdmin()))
        <a href="{{ route('admin.kyc') }}" class="{{ request()->routeIs('admin.kyc*') ? 'active' : '' }}">
            <i class="bi bi-file-check me-2"></i> KYC Management
        </a>
        @endif
        
        @if(Route::has('admin.analytics'))
        <a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
            <i class="bi bi-graph-up me-2"></i> Analytics
        </a>
        @endif
        
        <hr style="border-color: rgba(255,255,255,0.2);">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start" style="padding: 12px 20px;">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header Bar -->
        <div class="header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center">
                <div class="me-3 text-end d-none d-md-block">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <small class="text-muted">{{ auth()->user()->email }}</small>
                </div>
                <div class="avatar-circle bg-warning text-dark d-flex align-items-center justify-content-center rounded-circle fw-bold" style="width: 40px; height: 40px;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        function toggleSidebar() {
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        hamburger.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
