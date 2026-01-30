@extends('layouts.admin')

@section('title', 'Platform Analytics')

@section('content')
<div class="row mb-4">
    <!-- User Growth -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold mb-2">Total Users</h6>
                <div class="d-flex align-items-baseline">
                    <h2 class="mb-0 text-navy fw-bold">{{ \App\Models\User::count() }}</h2>
                    <span class="badg bg-soft-success text-success ms-2">
                        <i class="bi bi-arrow-up-short"></i> 12%
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Admins -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold mb-2">Active Admins</h6>
                <div class="d-flex align-items-baseline">
                    <h2 class="mb-0 text-navy fw-bold">{{ \App\Models\Admin::where('status', 'active')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- KYC Pending -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold mb-2">Pending KYC</h6>
                <div class="d-flex align-items-baseline">
                    <h2 class="mb-0 text-warning fw-bold">{{ \App\Models\KycSubmission::where('status', 'submitted')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects (Placeholder) -->
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted text-uppercase small fw-bold mb-2">Active Projects</h6>
                <div class="d-flex align-items-baseline">
                    <h2 class="mb-0 text-info fw-bold">0</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">User Growth Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart" height="150"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">User Distribution</h5>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="userDistributionChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // User Growth Chart
    const ctxGrowth = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(ctxGrowth, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Users',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#0F1A3C',
                backgroundColor: 'rgba(15, 26, 60, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // User Distribution Chart
    const ctxDist = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(ctxDist, {
        type: 'doughnut',
        data: {
            labels: ['Investors', 'Owners', 'Agents'],
            datasets: [{
                data: [
                    {{ \App\Models\User::where('membership_type', 'investor')->count() }},
                    {{ \App\Models\User::where('membership_type', 'owner')->count() }},
                    {{ \App\Models\User::where('membership_type', 'marketer')->count() }}
                ],
                backgroundColor: [
                    '#0F1A3C', // Navy
                    '#E4B400', // Gold
                    '#20c997'  // Cyan/Green
                ]
            }]
        },
        options: {
            responsive: true,
            cutout: '70%'
        }
    });
</script>

<style>
    .text-navy { color: #0F1A3C; }
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
</style>
@endsection
