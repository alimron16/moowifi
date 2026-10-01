@extends('layouts.base')

@section('body-class', 'd-flex flex-column bg-body-tertiary')

@section('content')
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="/" class="navbar-brand navbar-brand-autodark mb-1">
                <img src="{{ asset('logo.png') }}" alt="MooWiFi Logo" style="height: 58px; max-width: 220px; object-fit: contain;">
            </a>
            <div class="fs-2 fw-bold text-dark mt-1">MooWiFi</div>
            <div class="text-secondary small mt-1">Otomatisasi Jaringan, Maksimalkan Cuan.</div>
        </div>

        @if(session('success'))
            <x-alert type="success" class="mb-3">
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="danger" class="mb-3">
                {{ session('error') }}
            </x-alert>
        @endif

        @if($errors->any())
            <x-alert type="danger" class="mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        @yield('guest-content')

        <div class="text-center text-secondary mt-3 small">
            &copy; {{ date('Y') }} MooWiFi. Hak cipta dilindungi.
        </div>
    </div>
</div>
@endsection
