@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-wrapper">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-md-6 col-lg-5">
                <!-- Login Card -->
                <div class="card login-card" style="border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.4);">
                    <!-- Card Header with Gradient -->
                    <div style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); padding: 2rem; text-align: center;">
                        <div style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <i class="fas fa-gas-pump" style="font-size: 1.75rem; color: white;"></i>
                        </div>
                        <h1 style="color: white; margin: 0; font-size: 2rem; font-weight: 700; letter-spacing: -0.025em;">Fuel Station</h1>
                        <p style="color: rgba(255, 255, 255, 0.9); margin: 0.5rem 0 0; font-size: 0.875rem;">Management System</p>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body" style="padding: 2.5rem;">
                        <h2 style="color: #ffffff; text-align: center; margin-bottom: 0.5rem; font-size: 1.75rem; font-weight: 700;">Welcome Back</h2>
                        <p style="color: #94a3b8; text-align: center; margin-bottom: 2rem; font-size: 0.875rem;">Sign in to your account to continue</p>

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf

                            <!-- Name Input -->
                            <div class="mb-3">
                                <label for="name" class="form-label" style="color: #e2e8f0; font-weight: 500; font-size: 0.9rem;">Name</label>
                                <div style="position: relative;">
                                    <i class="fas fa-user" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #f59e0b; font-size: 1rem;"></i>
                                    <input
                                        type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="Enter your name"
                                        required
                                        autofocus
                                        style="background-color: #334155; border: 1px solid #475569; color: #ffffff; padding-left: 2.75rem; border-radius: 6px; transition: all 0.3s ease;"
                                    />
                                </div>
                                @error('name')
                                    <div class="invalid-feedback" style="color: #ef4444; display: block; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="mb-4">
                                <label for="password" class="form-label" style="color: #e2e8f0; font-weight: 500; font-size: 0.9rem;">Password</label>
                                <div style="position: relative;">
                                    <i class="fas fa-lock" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #f59e0b; font-size: 1rem;"></i>
                                    <input
                                        type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        id="password"
                                        name="password"
                                        placeholder="Enter your password"
                                        required
                                        autocomplete="current-password"
                                        style="background-color: #334155; border: 1px solid #475569; color: #ffffff; padding-left: 2.75rem; border-radius: 6px; transition: all 0.3s ease;"
                                    />
                                </div>
                                @error('password')
                                    <div class="invalid-feedback" style="color: #ef4444; display: block; font-size: 0.85rem; margin-top: 0.5rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%); color: white; padding: 0.875rem; font-weight: 600; border: none; border-radius: 6px; transition: all 0.3s ease; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 1rem;">
                                <i class="fas fa-sign-in-alt"></i> Sign In
                            </button>
                            
                        </form>
                    </div>
                </div>

                <!-- Footer Info -->
                <div style="text-align: center; margin-top: 2rem;">
                    <p style="color: #94a3b8; font-size: 0.85rem; margin: 0;">
                        &copy; 2026 Fuel Station Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .login-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .login-card {
        animation: slideInUp 0.6s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-control {
        transition: all 0.3s ease !important;
    }

    .form-control:focus {
        background-color: #334155 !important;
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2) !important;
        color: #ffffff !important;
    }

    .form-control::placeholder {
        color: #64748b !important;
    }

    button[type="submit"]:hover {
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -10px rgba(245, 158, 11, 0.4) !important;
    }

    button[type="submit"]:active {
        transform: translateY(0);
    }
</style>
@endsection