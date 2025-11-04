@extends('layouts.engaz')

@section('content')
<div class="auth-card">
    <h1>{{ __('auth.login') }}</h1>
    <form method="post" action="{{ route('login') }}">
        @csrf
        <label>
            {{ __('auth.email') }}
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label>
        <label>
            {{ __('auth.password') }}
            <input type="password" name="password" required>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="remember"> {{ __('auth.remember_me') }}
        </label>
        <button type="submit" class="btn-primary">{{ __('auth.login') }}</button>
        <div class="links">
            <a href="{{ route('password.request') }}">{{ __('auth.forgot_password') }}</a>
        </div>
    </form>
</div>
@endsection
