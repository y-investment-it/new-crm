@extends('layouts.engaz')

@section('content')
<div class="auth-card">
    <h1>{{ __('auth.reset_password') }}</h1>
    <form method="post" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <label>
            {{ __('auth.email') }}
            <input type="email" name="email" value="{{ old('email', $request->email) }}" required>
        </label>
        <label>
            {{ __('auth.password') }}
            <input type="password" name="password" required>
        </label>
        <label>
            {{ __('auth.password_confirmation') }}
            <input type="password" name="password_confirmation" required>
        </label>
        <button type="submit" class="btn-primary">{{ __('auth.reset_password') }}</button>
    </form>
</div>
@endsection
