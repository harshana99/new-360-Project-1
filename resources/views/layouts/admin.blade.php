<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - 360WinEstate</title>
    
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
            --sidebar-width: 260px;
        }

        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--bg-dark); 
            color: var(--text-main);
            overflow-x: hidden;
        }

        .text-muted { color: var(--text-muted) !important; }
        
        /* GLOWING GLASS EFFECT */
        body {
            background: radial-gradient(circle at top right, #1a233a 0%, #0B1221 60%), #0B1221;
        }

        .glass-card, .stats-card, .card, .table-dark-custom {
            background: rgba(21, 28, 47, 0.6) !important;
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .glass-card:hover, .stats-card:hover, .card:hover {
            box-shadow: 0 0 25px rgba(245, 158, 11, 0.15), inset 0 0 0 1px rgba(255, 255, 255, 0.1);
            border-color: rgba(245, 158, 11, 0.3) !important;
            transform: translateY(-3px);
        }

        /* Sidebar Glass Override */
        .sidebar {
            background: rgba(11, 18, 33, 0.95) !important; 
            backdrop-filter: blur(20px);
        }

        /* SIDEBAR */
        .sidebar {
            background: var(--bg-card); /* Slightly lighter for contrast or same as card? Layout used bg-dark usually. */
            background: #0B1221;
            border-right: 1px solid rgba(255,255,255,0.05);
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
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
            background: rgba(255,255,255,0.08); /* Subtle highlight */
            font-weight: 500;
        }
        .sidebar a i { font-size: 18px; }
        
        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
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
        .search-bar {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 10px 20px;
            color: white;
            width: 300px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-bar input { background: transparent; border: none; color: white; width: 100%; outline: none; }
        .search-bar i { color: var(--text-muted); }

        .header-actions { display: flex; align-items: center; gap: 20px; }
        .icon-btn { 
            background: var(--bg-card); 
            border: 1px solid rgba(255,255,255,0.05); 
            color: white; 
            width: 40px; height: 40px; 
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; 
            transition: 0.2s;
        }
        .icon-btn:hover { background: rgba(255,255,255,0.1); }
        
        .profile-pic {
            width: 40px; height: 40px; border-radius: 50%; background: #334155;
            display: flex; align-items: center; justify-content: center; font-weight: 600;
            color: white;
        }
        
        /* CARDS */
        .stats-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 25px;
            height: 100%;
            border: 1px solid rgba(255,255,255,0.03);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .stats-card h3 { font-size: 14px; color: white; opacity: 0.9; font-weight: 500; margin-bottom: 10px; letter-spacing: 0.5px; }
        .stats-card .value { font-size: 28px; font-weight: 700; color: white; margin-bottom: 5px; }
        .stats-card .subtext { font-size: 13px; color: var(--text-muted); }
        
        /* TABLES */
        .table-dark-custom {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-muted);
            border-color: rgba(255,255,255,0.05);
        }
        .table-dark-custom th {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 15px;
            color: white;
            font-weight: 500;
            font-size: 14px;
        }
        .table-dark-custom td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .table-dark-custom tr:last-child td { border-bottom: none; }

        /* RESPONSIVE */
        .hamburger { display: none; color: white; background: none; border: none; font-size: 24px; }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); z-index: 1050; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .hamburger { display: block; }
            
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
</head>
<body>

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <i class="bi bi-shield-lock-fill text-danger me-2"></i>
            Admin<span class="text-white">Panel</span>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a></li>
            
            <li class="small text-muted text-uppercase fw-bold px-3 mt-4 mb-2" style="font-size: 11px; letter-spacing: 1px;">Management</li>

            @if(auth()->user()->admin->isSuperAdmin())
            <li><a href="{{ route('admin.admins') }}" class="{{ request()->routeIs('admin.admins*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Manage Admins
            </a></li>
            @endif

            <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Users
            </a></li>

            <li><a href="{{ route('admin.properties.index') }}" class="{{ request()->routeIs('admin.properties*') ? 'active' : '' }}">
                <i class="bi bi-building-fill"></i> Properties
            </a></li>

            <li><a href="{{ route('admin.kyc') }}" class="{{ request()->routeIs('admin.kyc*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-person-fill"></i> KYC Requests
            </a></li>

            <li class="small text-muted text-uppercase fw-bold px-3 mt-4 mb-2" style="font-size: 11px; letter-spacing: 1px;">System</li>
            
            <li><a href="{{ route('admin.analytics') }}" class="{{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
                <i class="bi bi-graph-up"></i> Analytics
            </a></li>
        </ul>

        <div style="position: absolute; bottom: 30px; left: 20px; width: calc(100% - 40px);">
             <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; width: 100%; border-radius: 10px; padding: 12px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 8px;">
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
                <div>
                   <h4 class="fw-bold mb-0 text-white">@yield('title', 'Admin Dashboard')</h4> 
                   <small class="text-muted">Welcome back, Admin</small>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <div class="search-bar d-none d-md-flex">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search users, properties...">
                </div>

                <div class="header-actions">
                    <button class="icon-btn position-relative">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 8px; width: 6px; height: 6px; padding: 0;"></span>
                    </button>
                    <div class="profile-pic">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center mb-4" role="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10B981; color: #10B981;">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.sidebar-backdrop').classList.toggle('active');
        }
    </script>
</body>
</html>
