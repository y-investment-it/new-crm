@extends('layouts.engaz')

@section('content')
<div class="auth-card">
    <h1>{{ __('auth.forgot_password') }}</h1>
    <form method="post" action="{{ route('password.email') }}">
        @csrf
        <label>
            {{ __('auth.email') }}
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>
        <button type="submit" class="btn-primary">{{ __('auth.email_password_reset_link') }}</button>
    </form>
</div>
@endsection
