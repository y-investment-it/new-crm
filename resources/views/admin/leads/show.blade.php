@extends('layouts.engaz')

@section('content')
<div class="panel">
    <h1>{{ $lead->name }}</h1>
    <p><strong>{{ __('leads.phone') }}:</strong> {{ $lead->phone }}</p>
    <p><strong>{{ __('leads.email') }}:</strong> {{ $lead->email }}</p>
    <p><strong>{{ __('leads.stage') }}:</strong> {{ __('stages.' . $lead->stage) }}</p>
    <p><strong>{{ __('leads.owner') }}:</strong> {{ optional($lead->owner)->name }}</p>
    <p><strong>{{ __('leads.created_by') }}:</strong> {{ optional($lead->creator)->name }}</p>
</div>

<div class="grid layout-two">
    <div class="panel">
        <h2>{{ __('leads.comments') }}</h2>
        <ul class="list">
            @foreach($lead->comments as $comment)
                <li>
                    <div class="meta">{{ optional($comment->user)->name }} · {{ $comment->created_at?->diffForHumans() }}</div>
                    <div>{{ $comment->comment }}</div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="panel">
        <h2>{{ __('leads.history') }}</h2>
        <ul class="list">
            @foreach($lead->histories as $history)
                <li>
                    <div class="meta">{{ optional($history->user)->name }} · {{ $history->created_at?->diffForHumans() }}</div>
                    <div>{{ __('history.' . $history->action) }}</div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
