@extends('dashboards.layouts.dashboard-layout')
@section('title', 'My Investments')
@section('content')
<h2 class="fw-bold text-navy mb-4">My Investments</h2>
<x-dashboard-navigation role="investor" active="investments" />
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="bg-light"><tr><th>Property</th><th>Amount</th><th>Status</th><th>Returns</th></tr></thead>
            <tbody>
                <tr><td>Lekki Heights</td><td>₦500,000</td><td>Active</td><td>₦85,000</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
