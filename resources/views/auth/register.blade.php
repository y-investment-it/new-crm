@extends('layouts.engaz')

@section('content')
<div class="auth-card">
    <h1>{{ __('auth.register') }}</h1>
    <form method="post" action="{{ route('register') }}">
        @csrf
        <label>
            {{ __('auth.name') }}
            <input type="text" name="name" value="{{ old('name') }}" required>
        </label>
        <label>
            {{ __('auth.email') }}
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>
        <label>
            {{ __('auth.password') }}
            <input type="password" name="password" required>
        </label>
        <label>
            {{ __('auth.password_confirmation') }}
            <input type="password" name="password_confirmation" required>
        </label>
        <button type="submit" class="btn-primary">{{ __('auth.register') }}</button>
    </form>
</div>
@endsection
