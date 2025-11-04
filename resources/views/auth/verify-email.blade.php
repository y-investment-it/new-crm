@extends('layouts.engaz')

@section('content')
<div class="auth-card">
    <h1>{{ __('auth.verify_email') }}</h1>
    <p>{{ __('auth.verify_email_message') }}</p>
    <form method="post" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-primary">{{ __('auth.resend_verification') }}</button>
    </form>
</div>
@endsection
