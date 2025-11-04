@extends('layouts.engaz')

@section('content')
<div class="panel">
    <form method="get" class="filters">
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('leads.search_placeholder') }}">
        <select name="stage">
            <option value="">{{ __('leads.all_stages') }}</option>
            @foreach(\App\Models\Lead::STAGES as $stage)
                <option value="{{ $stage }}" @selected(($filters['stage'] ?? '') === $stage)>{{ __('stages.' . $stage) }}</option>
            @endforeach
        </select>
        <select name="owner_id">
            <option value="">{{ __('leads.all_owners') }}</option>
            @foreach($owners as $owner)
                <option value="{{ $owner->id }}" @selected(($filters['owner_id'] ?? '') == $owner->id)>{{ $owner->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary">{{ __('leads.filter') }}</button>
    </form>
</div>

<form id="lead-selection" class="panel">
    <div class="actions-row">
        <button type="button" class="btn-secondary" id="reassign-trigger">{{ __('leads.reassign') }}</button>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>{{ __('leads.name') }}</th>
            <th>{{ __('leads.phone') }}</th>
            <th>{{ __('leads.stage') }}</th>
            <th>{{ __('leads.owner') }}</th>
            <th>{{ __('leads.updated') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($leads as $lead)
            <tr>
                <td><input type="checkbox" value="{{ $lead->id }}" class="lead-checkbox"></td>
                <td>{{ $lead->name }}</td>
                <td>{{ $lead->phone }}</td>
                <td>{{ __('stages.' . $lead->stage) }}</td>
                <td>{{ optional($lead->owner)->name }}</td>
                <td>{{ $lead->updated_at->diffForHumans() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $leads->links() }}
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('reassign-modal');
        const trigger = document.getElementById('reassign-trigger');
        const closeBtn = document.getElementById('modal-close');
        const checkAll = document.getElementById('select-all');
        const checkboxes = Array.from(document.querySelectorAll('.lead-checkbox'));
        const syncSelection = () => {
            const selected = checkboxes.filter(cb => cb.checked).map(cb => Number(cb.value));
            if (window.adminApp) {
                window.adminApp.selectedIds = selected;
            }
        };

        checkboxes.forEach(cb => cb.addEventListener('change', syncSelection));
        checkAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = checkAll.checked);
            syncSelection();
        });

        trigger.addEventListener('click', () => {
            syncSelection();
            if (!window.adminApp || !window.adminApp.selectedIds.length) {
                alert('{{ __('leads.select_warning') }}');
                return;
            }
            modal.hidden = false;
            document.body.classList.add('modal-open');
        });

        closeBtn.addEventListener('click', () => {
            modal.hidden = true;
            document.body.classList.remove('modal-open');
        });

        window.addEventListener('reassign-complete', () => window.location.reload());
    });
</script>
@endsection

@section('vue-app')
<div class="modal" id="reassign-modal" hidden>
    <div class="modal-content">
        <button class="modal-close" type="button" id="modal-close">×</button>
        <re-assign-leads :lead-ids="state.selectedIds" @updated="() => window.dispatchEvent(new CustomEvent('reassign-complete'))"></re-assign-leads>
    </div>
</div>
@endsection
