@extends('layouts.engaz')

@section('content')
<div class="grid layout-two">
    <div class="panel">
        <h2>{{ __('reports.by_stage') }}</h2>
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('reports.stage') }}</th>
                <th>{{ __('reports.count') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($stageSummary as $row)
                <tr>
                    <td>{{ __('stages.' . $row->stage) }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="panel">
        <h2>{{ __('reports.created_daily') }}</h2>
        <table class="table">
            <thead>
            <tr>
                <th>{{ __('reports.date') }}</th>
                <th>{{ __('reports.count') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($createdPerDay as $row)
                <tr>
                    <td>{{ $row->day }}</td>
                    <td>{{ $row->total }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="panel">
    <h2>{{ __('reports.top_agents') }}</h2>
    <table class="table">
        <thead>
        <tr>
            <th>{{ __('reports.agent') }}</th>
            <th>{{ __('reports.won') }}</th>
            <th>{{ __('reports.activity') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($topAgents as $agent)
            <tr>
                <td>{{ $agent->name }}</td>
                <td>{{ $agent->won_leads_count }}</td>
                <td>{{ $agent->activity_count ?? 0 }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
