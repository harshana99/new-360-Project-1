@extends('dashboards.layouts.dashboard-layout')
@section('title', 'My Properties')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-navy mb-0">My Properties</h2>
    <button class="btn btn-gold">Add Property</button>
</div>
<x-dashboard-navigation role="owner" active="properties" />
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr><th>#</th><th>Property Name</th><th>Location</th><th>Status</th><th>Value</th><th>Earnings</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>Lekki Heights</td><td>Lekki, Lagos</td><td><span class="badge bg-success">Active</span></td><td>₦50,000,000</td><td>₦2,300,000</td><td><button class="btn btn-sm btn-outline-primary">View</button></td></tr>
                    <tr><td>2</td><td>Victoria Island Apt</td><td>Victoria Island</td><td><span class="badge bg-success">Active</span></td><td>₦75,000,000</td><td>₦3,100,000</td><td><button class="btn btn-sm btn-outline-primary">View</button></td></tr>
                    <tr><td>3</td><td>Ikoyi Flat</td><td>Ikoyi</td><td><span class="badge bg-warning text-dark">Pending</span></td><td>₦25,000,000</td><td>₦0</td><td><button class="btn btn-sm btn-outline-primary">Edit</button></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
