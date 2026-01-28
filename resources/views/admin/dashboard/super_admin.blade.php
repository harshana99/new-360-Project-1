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
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 p-0">
                <div class="sidebar">
                    <div class="logo">
                        360<span class="gold">Win</span>Estate
                    </div>
                    <hr style="border-color: rgba(255,255,255,0.2);">
                    <a href="{{ route('admin.dashboard') }}" class="active">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.admins') }}">
                        <i class="bi bi-people me-2"></i> Manage Admins
                    </a>
                    <a href="{{ route('admin.users') }}">
                        <i class="bi bi-person me-2"></i> Users
                    </a>
                    <a href="{{ route('admin.kyc') }}">
                        <i class="bi bi-file-check me-2"></i> KYC Management
                    </a>
                    <a href="{{ route('admin.analytics') }}">
                        <i class="bi bi-graph-up me-2"></i> Analytics
                    </a>
                    <hr style="border-color: rgba(255,255,255,0.2);">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link text-white text-decoration-none w-100 text-start" style="padding: 12px 20px;">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 p-0">
                <div class="main-content">
                    <!-- Header -->
                    <div class="header">
                        <h2 class="mb-0">Super Admin Dashboard</h2>
                        <p class="mb-0">Welcome, <strong>{{ $user->name }}</strong>! <span class="badge-super">Super Admin</span></p>
                    </div>

                    <!-- Success Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Stats Cards -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <i class="bi bi-people icon"></i>
                                <h3>{{ $totalUsers }}</h3>
                                <p>Total Users</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <i class="bi bi-check-circle icon"></i>
                                <h3>{{ $approvedUsers }}</h3>
                                <p>Approved Users</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card">
                                <i class="bi bi-shield-check icon"></i>
                                <h3>{{ $activeAdmins }}</h3>
                                <p>Active Admins</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.create') }}" class="btn btn-gold me-2">
                                <i class="bi bi-plus-circle me-2"></i>Create New Admin
                            </a>
                            <a href="{{ route('admin.admins') }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-people me-2"></i>Manage Admins
                            </a>
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-person me-2"></i>View All Users
                            </a>
                        </div>
                    </div>

                    <!-- Pending Items -->
                    <div class="card mt-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Pending Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-text text-warning me-3" style="font-size: 30px;"></i>
                                        <div>
                                            <h4 class="mb-0">{{ $pendingKYC }}</h4>
                                            <small class="text-muted">Pending KYC</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Admins -->
                    @if($recentAdmins->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Recent Admins</h5>
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
                                        @foreach($recentAdmins as $recentAdmin)
                                        <tr>
                                            <td>{{ $recentAdmin->user->name }}</td>
                                            <td><span class="badge {{ $recentAdmin->getRoleBadgeClass() }}">{{ $recentAdmin->getRoleLabel() }}</span></td>
                                            <td><span class="badge {{ $recentAdmin->getStatusBadgeClass() }}">{{ ucfirst($recentAdmin->status) }}</span></td>
                                            <td>{{ $recentAdmin->created_at->diffForHumans() }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
