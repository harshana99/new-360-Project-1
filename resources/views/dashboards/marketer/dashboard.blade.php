@extends('dashboards.layouts.dashboard-layout')

@section('title', 'Marketer Dashboard')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <!-- Dashboard Navigation Tabs -->
        <ul class="nav nav-pills mb-4" style="gap: 10px;">
            <li class="nav-item">
                <a class="nav-link active bg-white text-dark fw-bold" href="{{ route('marketer.dashboard') }}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-muted" href="{{ route('marketer.team') }}">My Team</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-muted" href="{{ route('marketer.commissions') }}">Commissions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-muted" href="{{ route('marketer.leaderboard') }}">Leaderboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-muted" href="{{ route('marketer.targets') }}">Targets</a>
            </li>
        </ul>
        
        <!-- ROW 1: Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stats-card bg-white d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Total Referrals</h3>
                        <div class="value text-dark" style="font-size: 32px;">{{ $metrics->total_referrals }}</div>
                    </div>
                    <div class="text-end text-primary">
                        <i class="bi bi-people" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card bg-white d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Commission Earned</h3>
                        <div class="value text-dark" style="font-size: 32px;">₦{{ number_format($metrics->total_commission_earned, 2) }}</div>
                        <div class="text-success" style="font-size: 12px;">
                            <i class="bi bi-arrow-up"></i> 15% vs last month
                        </div>
                    </div>
                    <div class="text-end text-success">
                        <i class="bi bi-cash-stack" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card bg-white d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Current Level</h3>
                        <div class="value text-dark" style="font-size: 32px;">{{ $metrics->current_level }}</div>
                    </div>
                    <div class="text-end text-warning">
                        <i class="bi bi-trophy" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card bg-white d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="text-uppercase text-muted" style="font-size: 12px; letter-spacing: 1px;">Pending Commission</h3>
                        <div class="value text-dark" style="font-size: 32px;">₦{{ number_format($metrics->pending_commission, 2) }}</div>
                    </div>
                    <div class="text-end text-info">
                        <i class="bi bi-hourglass-split" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ROW 2: Main Area -->
        <div class="row g-4">
            <!-- Team Hierarchy -->
            <div class="col-lg-8">
                <div class="stats-card bg-white h-100">
                    <h5 class="text-dark fw-bold mb-4">Team Hierarchy</h5>
                    
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 250px;">
                        <!-- Simple Visual Representation of Visual Tree -->
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="bi bi-diagram-3 text-muted" style="font-size: 40px;"></i>
                            </div>
                            <h6 class="text-dark">{{ $user->name }}'s Team</h6>
                            
                            <div class="mt-4 text-start bg-light p-4 rounded-3 d-inline-block">
                                <div class="mb-2">
                                    <i class="bi bi-person-circle text-primary me-2"></i> <strong>You</strong> <span class="badge bg-warning text-dark ms-2">{{ $metrics->current_level }}</span>
                                </div>
                                <div class="ps-4 border-start border-2 border-primary ms-2">
                                    @forelse($teamHierarchy->take(3) as $member)
                                        <div class="mb-2 position-relative" style="left: 10px;">
                                            <i class="bi bi-arrow-return-right text-muted me-2"></i> {{ $member['name'] }}
                                        </div>
                                    @empty
                                        <div class="text-muted fst-italic ps-3 pt-2">No team members yet. Invite someone!</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Commission Breakdown Chart -->
            <div class="col-lg-4">
                <div class="stats-card bg-white h-100">
                    <h5 class="text-dark fw-bold mb-4">Commission Breakdown</h5>
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                        <canvas id="commissionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('commissionChart').getContext('2d');
    
    const breakdownData = {!! json_encode($commissionBreakdown) !!};
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Direct Sales', 'Team Override', 'Bonuses'],
            datasets: [{
                data: [
                    breakdownData.direct ?? 65, 
                    breakdownData.team ?? 25, 
                    breakdownData.bonus ?? 10
                ],
                backgroundColor: [
                    '#F59E0B', // Gold
                    '#10B981', // Green
                    '#0F1A3C'  // Navy
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
