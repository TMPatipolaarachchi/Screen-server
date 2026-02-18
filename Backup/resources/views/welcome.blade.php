@extends('layouts.auth')

@section('title', 'Welcome')

@section('content')
<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-5 text-center">
                    <h1 class="card-title mb-4">
                        <i class="fas fa-gas-pump me-2" style="color: var(--primary);"></i> {{ config('app.name', 'Fuel Station') }}
                    </h1>
                    <p class="card-text mb-4">
                        Welcome to the Fuel Station Management System. Please log in to access your account or register for a new account.
                    </p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/home') }}" class="btn btn-primary">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-secondary">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection