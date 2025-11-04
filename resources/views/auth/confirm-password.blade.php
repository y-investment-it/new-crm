@extends('layouts.engaz')

@section('content')
<div class="auth-card">
    <h1>{{ __('auth.confirm_password') }}</h1>
    <form method="post" action="{{ route('password.confirm') }}">
        @csrf
        <label>
            {{ __('auth.password') }}
            <input type="password" name="password" required>
        </label>
        <button type="submit" class="btn-primary">{{ __('auth.confirm_password') }}</button>
    </form>
</div>
@endsection
