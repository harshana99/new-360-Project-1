@extends('dashboards.layouts.dashboard-layout')

@section('title', 'Earnings')

@push('styles')
<style>
    /* Custom Dark Table Styling */
    .earnings-table {
        border-collapse: separate; 
        border-spacing: 0 10px; 
        width: 100%;
    }
    .earnings-table thead th {
        background: transparent !important;
        border: none;
        color: var(--text-muted);
        font-weight: 500;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0 15px 10px 15px;
    }
    .earnings-table tbody tr {
        background-color: #1E293B !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .earnings-table tbody tr:hover {
        transform: translateY(-2px);
        background-color: #26334D !important;
    }
    .earnings-table td {
        background: transparent !important;
        border: none;
        padding: 20px 15px;
        vertical-align: middle;
        color: white;
    }
    .earnings-table td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .earnings-table td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

    /* Tabs Styling */
    .nav-tabs { border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 30px; }
    .nav-tabs .nav-link { color: var(--text-muted); background: transparent; border: none; padding-bottom: 15px; position: relative; }
    .nav-tabs .nav-link:hover { color: white; }
    .nav-tabs .nav-link.active { color: var(--accent-gold); background: transparent; font-weight: 600; }
    .nav-tabs .nav-link.active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: var(--accent-gold); }
</style>
@endpush

@section('content')
<h2 class="fw-bold text-white mb-4">Earnings History</h2>

<!-- Custom Navigation Tabs -->
<ul class="nav nav-tabs">
    <li class="nav-item"><a class="nav-link" href="{{ route('owner.dashboard') }}">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('owner.properties') }}">My Properties</a></li>
    <li class="nav-item"><a class="nav-link active" href="#">Earnings</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('owner.documents') }}">Documents</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('owner.analytics') }}">Analytics</a></li>
</ul>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stats-card p-4">
            <h6 class="text-white text-opacity-75 text-uppercase small">Total Earnings</h6>
            <h2 class="text-white fw-bold mb-0">₦{{ number_format(180000) }}</h2> <!-- Placeholder/Real Data -->
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card p-4">
            <h6 class="text-white text-opacity-75 text-uppercase small">This Month</h6>
            <h2 class="text-success fw-bold mb-0">+₦45,000</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card p-4">
            <h6 class="text-white text-opacity-75 text-uppercase small">Pending Payouts</h6>
            <h2 class="text-warning fw-bold mb-0">₦12,500</h2>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="stats-card p-4" style="min-height: 400px;">
    <h5 class="fw-bold text-white mb-4">Transactions</h5>
    <div class="table-responsive">
        <table class="table earnings-table">
            <thead>
                <tr>
                    <th class="ps-3">Property</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end pe-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions ?? [] as $transaction)
                <tr>
                    <td class="ps-3 fw-bold">{{ $transaction->property->title ?? 'N/A' }}</td>
                    <td><span class="badge" style="background: rgba(255,255,255,0.1); color: white;">Rental Income</span></td>
                    <td class="text-success fw-bold">+₦{{ number_format($transaction->amount ?? 0) }}</td>
                    <td><span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10B981;">Completed</span></td>
                    <td class="text-muted">{{ $transaction->created_at ? $transaction->created_at->format('M d, Y') : 'Jan 01, 2026' }}</td>
                    <td class="text-end pe-3"><button class="btn btn-sm btn-outline-light"><i class="bi bi-download"></i></button></td>
                </tr>
                @empty
                <!-- Hardcoded Placeholders to look good if no data -->
                <tr>
                    <td class="ps-3 fw-bold">Lekki Heights</td>
                    <td><span class="badge" style="background: rgba(255,255,255,0.1); color: white;">Rental Income</span></td>
                    <td class="text-success fw-bold">+₦100,000</td>
                    <td><span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10B981;">Completed</span></td>
                    <td class="text-muted">Jan 28, 2026</td>
                    <td class="text-end pe-3"><button class="btn btn-sm btn-outline-light"><i class="bi bi-download"></i></button></td>
                </tr>
                <tr>
                    <td class="ps-3 fw-bold">Victoria Island Apt</td>
                    <td><span class="badge" style="background: rgba(255,255,255,0.1); color: white;">Rental Income</span></td>
                    <td class="text-success fw-bold">+₦80,000</td>
                    <td><span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10B981;">Completed</span></td>
                    <td class="text-muted">Jan 15, 2026</td>
                    <td class="text-end pe-3"><button class="btn btn-sm btn-outline-light"><i class="bi bi-download"></i></button></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
