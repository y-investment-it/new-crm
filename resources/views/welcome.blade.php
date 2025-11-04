<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CRM Y') }}</title>
    <link rel="stylesheet" href="{{ asset('css/engaz.css') }}">
</head>
<body class="welcome">
    <div class="welcome-inner">
        <h1>{{ config('app.name', 'CRM Y') }}</h1>
        <p>{{ __('auth.welcome_message') }}</p>
        <div class="actions">
            <a href="{{ route('login') }}" class="btn-primary">{{ __('auth.login') }}</a>
            <a href="{{ route('register') }}" class="btn-secondary">{{ __('auth.register') }}</a>
        </div>
    </div>
</body>
</html>
