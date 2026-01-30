@extends('dashboards.layouts.dashboard-layout')

@section('title', 'Investor Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-navy mb-1">Investor Dashboard</h2>
            <p class="text-muted">Welcome back, {{ $user->name }}!</p>
        </div>
        <a href="{{ route('investor.investments') }}" class="btn btn-gold shadow-sm">
            <i class="bi bi-search me-2"></i>Browse Properties
        </a>
    </div>

    <!-- Navigation -->
    <x-dashboard-navigation role="investor" active="dashboard" />

    <!-- Metrics Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <x-dashboard-metrics 
                title="Total Invested" 
                value="₦{{ number_format($metrics->total_invested, 2) }}" 
                icon="bi-cash-coin" 
                color="primary" />
        </div>
        <div class="col-md-3">
            <x-dashboard-metrics 
                title="Total Returns" 
                value="₦{{ number_format($metrics->total_returns, 2) }}" 
                icon="bi-graph-up-arrow" 
                color="success"
                trend="8.5" />
        </div>
        <div class="col-md-3">
            <x-dashboard-metrics 
                title="ROI Percentage" 
                value="{{ $metrics->total_roi_percentage }}%" 
                icon="bi-percent" 
                color="info" />
        </div>
        <div class="col-md-3">
            <x-dashboard-metrics 
                title="Active Investments" 
                value="{{ $metrics->active_investments }}" 
                icon="bi-check-circle" 
                color="warning" />
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-navy">Returns Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="returnsTrendChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-navy">Portfolio Allocation</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="portfolioChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Dividends -->
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-navy">Upcoming Dividends</h5>
                    <a href="{{ route('investor.dividends') }}" class="btn btn-sm btn-outline-secondary">View Schedule</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Property</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingDividends as $dividend)
                            <tr>
                                <td class="ps-4">{{ $dividend['date'] }}</td>
                                <td>{{ $dividend['property'] }}</td>
                                <td>
                                    <span class="badge bg-soft-info text-info">
                                        {{ ucfirst($dividend['status']) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 fw-bold">
                                    ₦{{ number_format($dividend['amount'], 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No upcoming dividends.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Returns Chart
    const ctxReturns = document.getElementById('returnsTrendChart').getContext('2d');
    new Chart(ctxReturns, {
        type: 'line',
        data: {
            labels: {!! json_encode($performanceChart['labels']) !!},
            datasets: [{
                label: 'ROI %',
                data: {!! json_encode($performanceChart['roi']) !!},
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
             plugins: { legend: { display: false } }
        }
    });

    // Portfolio Chart
    const ctxPortfolio = document.getElementById('portfolioChart').getContext('2d');
    new Chart(ctxPortfolio, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($portfolio['labels']) !!},
            datasets: [{
                data: {!! json_encode($portfolio['data']) !!},
                backgroundColor: ['#0F1A3C', '#E4B400', '#20c997']
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
