<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - 360WinEstate</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .icon-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
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
            border-color: #667eea;
            background: #f8f9ff;
            transform: translateX(5px);
        }

        .action-btn i {
            font-size: 1.5rem;
            color: #667eea;
        }

        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .badge-role {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-building"></i> 360WinEstate
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="badge badge-role">
                    <i class="bi bi-person-badge"></i> Owner
                </span>
                <span class="text-muted">{{ $user->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
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
            <h1><i class="bi bi-house-heart"></i> Welcome back, {{ $user->name }}!</h1>
            <p>Here's an overview of your property portfolio</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- My Properties -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-primary">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <div class="stats-label">My Properties</div>
                    <div class="stats-value">{{ $stats->properties_count }}</div>
                    <div class="stats-change text-success">
                        <i class="bi bi-arrow-up"></i> {{ $stats->active_properties }} Active
                    </div>
                </div>
            </div>

            <!-- Property Value -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stats-label">Property Value</div>
                    <div class="stats-value">{{ $stats->getFormattedPropertyValue() }}</div>
                    <div class="stats-change text-muted">
                        <i class="bi bi-graph-up"></i> Total Portfolio
                    </div>
                </div>
            </div>

            <!-- Rental Income -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-warning">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="stats-label">Rental Income</div>
                    <div class="stats-value">{{ $stats->getFormattedRentalIncome() }}</div>
                    <div class="stats-change text-success">
                        <i class="bi bi-arrow-up"></i> Monthly
                    </div>
                </div>
            </div>

            <!-- Occupancy Rate -->
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon icon-info">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="stats-label">Occupancy Rate</div>
                    <div class="stats-value">{{ $stats->getOccupancyRate() }}%</div>
                    <div class="stats-change text-muted">
                        {{ $stats->rented_properties }}/{{ $stats->properties_count }} Rented
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row -->
        <div class="row">
            <!-- Maintenance Tickets -->
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon icon-danger">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div class="stats-label">Maintenance Tickets</div>
                    <div class="stats-value">{{ $stats->maintenance_tickets }}</div>
                    <div class="stats-change text-warning">
                        <i class="bi bi-exclamation-triangle"></i> Pending
                    </div>
                </div>
            </div>

            <!-- Service Apartments -->
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon icon-success">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <div class="stats-label">Service Apartments</div>
                    <div class="stats-value">{{ $stats->service_apartment_enrollments }}</div>
                    <div class="stats-change text-success">
                        <i class="bi bi-check-circle"></i> Enrolled
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-icon icon-primary">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="stats-label">Monthly Revenue</div>
                    <div class="stats-value">{{ $stats->getFormattedMonthlyRevenue() }}</div>
                    <div class="stats-change text-success">
                        <i class="bi bi-arrow-up"></i> This Month
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
                                <i class="bi bi-plus-circle"></i>
                                <div>
                                    <strong>Add New Property</strong>
                                    <div class="text-muted small">List a new property</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="action-btn">
                                <i class="bi bi-file-earmark-text"></i>
                                <div>
                                    <strong>View Reports</strong>
                                    <div class="text-muted small">Financial reports</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="action-btn">
                                <i class="bi bi-gear"></i>
                                <div>
                                    <strong>Manage Properties</strong>
                                    <div class="text-muted small">Edit property details</div>
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
