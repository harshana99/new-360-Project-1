@props(['role', 'active'])

@php
    $tabs = [];
    if($role === 'owner') {
        $tabs = [
            'dashboard' => ['label' => 'Dashboard', 'route' => 'owner.dashboard'],
            'properties' => ['label' => 'My Properties', 'route' => 'owner.properties'],
            'earnings' => ['label' => 'Earnings', 'route' => 'owner.earnings'],
            'documents' => ['label' => 'Documents', 'route' => 'owner.documents'],
            'analytics' => ['label' => 'Analytics', 'route' => 'owner.analytics'],
        ];
    } elseif($role === 'investor') {
        $tabs = [
            'dashboard' => ['label' => 'Dashboard', 'route' => 'investor.dashboard'],
            'investments' => ['label' => 'My Investments', 'route' => 'investor.investments'],
            'portfolio' => ['label' => 'Portfolio', 'route' => 'investor.portfolio'],
            'dividends' => ['label' => 'Dividends', 'route' => 'investor.dividends'],
            'performance' => ['label' => 'Performance', 'route' => 'investor.performance'],
        ];
    } elseif($role === 'marketer') {
        $tabs = [
            'dashboard' => ['label' => 'Dashboard', 'route' => 'marketer.dashboard'],
            'team' => ['label' => 'My Team', 'route' => 'marketer.team'],
            'commissions' => ['label' => 'Commissions', 'route' => 'marketer.commissions'],
            'leaderboard' => ['label' => 'Leaderboard', 'route' => 'marketer.leaderboard'],
            'targets' => ['label' => 'Targets', 'route' => 'marketer.targets'],
        ];
    }
@endphp

<div class="mb-4">
    <ul class="nav nav-tabs">
        @foreach($tabs as $key => $tab)
            <li class="nav-item">
                <a class="nav-link {{ $active === $key ? 'active' : '' }}" href="{{ route($tab['route']) }}">
                    {{ $tab['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
