@extends('layouts.engaz')

@section('content')
<div class="grid stats">
    <div class="card">
        <div class="label">{{ __('dashboard.all_leads') }}</div>
        <div class="value">{{ $stats['all'] }}</div>
    </div>
    <div class="card">
        <div class="label">{{ __('dashboard.duplicate_leads') }}</div>
        <div class="value">{{ $stats['duplicates'] }}</div>
    </div>
    <div class="card">
        <div class="label">{{ __('dashboard.fresh_leads') }}</div>
        <div class="value">{{ $stats['fresh'] }}</div>
    </div>
    <div class="card">
        <div class="label">{{ __('dashboard.cold_calls') }}</div>
        <div class="value">{{ $stats['cold'] }}</div>
    </div>
</div>

<div class="tabs">
    @foreach($delays as $stage => $count)
        <div class="tab">
            <span class="stage">{{ __('stages.' . $stage) }}</span>
            <span class="count">{{ $count }}</span>
        </div>
    @endforeach
</div>

<div class="grid layout-two">
    <div class="panel">
        <h2>{{ __('dashboard.leads_table') }}</h2>
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('dashboard.lead_name') }}</th>
                <th>{{ __('dashboard.phone') }}</th>
                <th>{{ __('dashboard.stage_date') }}</th>
                <th>{{ __('dashboard.last_comment') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($recentLeads as $lead)
                <tr>
                    <td>{{ $lead->name }}</td>
                    <td>{{ $lead->phone }}</td>
                    <td>{{ optional($lead->stage_date)->format('Y-m-d') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($lead->last_comment, 40) }}</td>
                    <td><a href="{{ route('admin.leads.show', $lead) }}" class="link">{{ __('dashboard.preview') }}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="panel">
        <h2>{{ __('dashboard.the_best') }}</h2>
        <ul class="rank-list">
            @foreach($topUsers as $user)
                <li>
                    <div class="name">{{ $user->name }}</div>
                    <div class="meta">{{ __('dashboard.won_leads') }}: {{ $user->won_leads_count }} | {{ __('dashboard.activity') }}: {{ $user->activity_count ?? 0 }}</div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
