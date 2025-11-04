<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CRM Y') }}</title>
    <link rel="stylesheet" href="{{ asset('css/engaz.css') }}">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body class="engaz-layout">
<div class="layout-grid">
    <aside class="sidebar">
        <div class="brand">CRM Y</div>
        <nav>
            <a href="{{ route('admin.dashboard') }}">{{ __('sidebar.dashboard') }}</a>
            <a href="{{ route('admin.leads.index') }}">{{ __('sidebar.leads') }}</a>
            <a href="#">{{ __('sidebar.tele_leads') }}</a>
            <a href="#">{{ __('sidebar.referrals') }}</a>
            <a href="#">{{ __('sidebar.owners') }}</a>
            <a href="#">{{ __('sidebar.inventory') }}</a>
            <a href="#">{{ __('sidebar.collections') }}</a>
            <a href="{{ route('admin.reports.index') }}">{{ __('sidebar.reports') }}</a>
            <a href="#">{{ __('sidebar.users') }}</a>
            <a href="#">{{ __('sidebar.exports') }}</a>
            <a href="#">{{ __('sidebar.imports') }}</a>
        </nav>
    </aside>
    <main class="main">
        <header class="header">
            <form class="search">
                <input type="search" placeholder="{{ __('sidebar.search_placeholder') }}">
            </form>
            <div class="actions">
                <button class="btn-primary">{{ __('sidebar.explore_button') }}</button>
                <div class="icons">
                    <span class="icon">🔔</span>
                    <span class="icon">⚙️</span>
                </div>
            </div>
        </header>
        <section class="content">
            {{ $slot ?? '' }}
            @yield('content')
        </section>
    </main>
</div>
<div id="admin-app">
    @yield('vue-app')
</div>
</body>
</html>
