@extends('layouts.admin')

@section('title', 'Finance Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Paid</h6>
                        <h3 class="mb-0">${{ number_format($totalPaid, 2) }}</h3>
                    </div>
                    <i class="bi bi-wallet2 fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Pending Payments</h6>
                        <h3 class="mb-0">${{ number_format($pendingPayments, 2) }}</h3>
                    </div>
                    <i class="bi bi-clock-history fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card stat-card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">This Month</h6>
                        <h3 class="mb-0">${{ number_format($monthTotal, 2) }}</h3>
                    </div>
                    <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle-fill me-2"></i> Payment and Commission management modules are currently under development.
</div>
@endsection
