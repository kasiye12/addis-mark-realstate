@extends('layouts.frontend')

@section('title', 'Sign In - ' . setting('site_name', 'Addis Mark Real Estate'))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
    }
    
    .auth-container {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
    
    .auth-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 440px;
        padding: 2.5rem 2rem;
        animation: slideUp 0.5s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    /* Logo Styles - Larger */
    .auth-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .auth-logo img {
        height: 60px;
        width: auto;
        max-width: 100%;
    }
    
    .auth-logo .logo-text {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
    }
    
    .auth-logo .logo-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
    }
    
    .auth-logo .logo-icon i {
        font-size: 30px;
        color: white;
    }
    
    .auth-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }
    
    .auth-subtitle {
        font-size: 15px;
        color: #6b7280;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 18px;
        transition: color 0.2s ease;
    }
    
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.75rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 14px;
        font-size: 15px;
        color: #1a1a1a;
        transition: all 0.2s ease;
        background: white;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .form-input:focus + .input-icon,
    .input-wrapper:focus-within .input-icon {
        color: #2563eb;
    }
    
    .form-input.error {
        border-color: #ef4444;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 13px;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .password-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        cursor: pointer;
        transition: color 0.2s ease;
        font-size: 18px;
    }
    
    .password-toggle:hover {
        color: #6b7280;
    }
    
    .forgot-link {
        text-align: right;
        margin-top: 0.25rem;
    }
    
    .forgot-link a {
        font-size: 13px;
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
    }
    
    .forgot-link a:hover {
        text-decoration: underline;
    }
    
    .remember-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .remember-checkbox input {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid #d1d5db;
        accent-color: #2563eb;
        cursor: pointer;
    }
    
    .remember-checkbox label {
        font-size: 14px;
        color: #4b5563;
        cursor: pointer;
    }
    
    .submit-btn {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 0.5rem;
        box-shadow: 0 8px 16px -4px rgba(37, 99, 235, 0.3);
    }
    
    .submit-btn:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-1px);
        box-shadow: 0 12px 20px -6px rgba(37, 99, 235, 0.4);
    }
    
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 1.5rem 0;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .divider span {
        padding: 0 0.75rem;
        font-size: 13px;
        color: #9ca3af;
        text-transform: lowercase;
    }
    
    .social-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
    
    .social-btn {
        flex: 1;
        padding: 0.75rem;
        border-radius: 14px;
        border: 1.5px solid #e5e7eb;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
    }
    
    .social-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }
    
    .social-btn i {
        font-size: 20px;
    }
    
    .register-link {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 14px;
        color: #6b7280;
    }
    
    .register-link a {
        color: #2563eb;
        font-weight: 600;
        text-decoration: none;
        margin-left: 0.25rem;
    }
    
    .register-link a:hover {
        text-decoration: underline;
    }
    
    .alert {
        padding: 0.875rem 1rem;
        border-radius: 12px;
        font-size: 14px;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
    }
    
    .alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
    }
    
    .back-home {
        text-align: center;
        margin-top: 1.5rem;
    }
    
    .back-home a {
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s ease;
    }
    
    .back-home a:hover {
        color: #2563eb;
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <!-- Logo - Larger and Centered -->
        <div class="auth-logo">
            @php
                $logoPath = \App\Models\Setting::get('site_logo');
                $siteName = \App\Models\Setting::get('site_name', 'Addis Mark');
                $logoUrl = $logoPath && \Storage::disk('public')->exists($logoPath) 
                    ? route('file.show', ['path' => $logoPath]) 
                    : null;
            @endphp
            
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}">
            @else
                <div class="logo-icon">
                    <i class="ri-building-line"></i>
                </div>
                <span class="logo-text">{{ $siteName }}</span>
            @endif
        </div>

        <!-- Header - No Icon -->
        <div class="auth-header">
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your account</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div class="alert alert-success">
                <i class="ri-check-line"></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-error">
                <i class="ri-error-warning-line"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="ri-mail-line input-icon"></i>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           class="form-input @error('email') error @enderror"
                           placeholder="your@email.com">
                </div>
                @error('email')
                    <p class="error-message">
                        <i class="ri-error-warning-line"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="ri-lock-line input-icon"></i>
                    <input type="password" 
                           name="password" 
                           id="password"
                           required
                           class="form-input @error('password') error @enderror"
                           placeholder="••••••••">
                    <span class="password-toggle" onclick="togglePassword()">
                        <i class="ri-eye-line" id="passwordToggleIcon"></i>
                    </span>
                </div>
                <div class="forgot-link">
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                </div>
                @error('password')
                    <p class="error-message">
                        <i class="ri-error-warning-line"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="remember-checkbox">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me</label>
            </div>

            <!-- Submit -->
            <button type="submit" class="submit-btn">
                <i class="ri-login-box-line mr-2"></i> Sign In
            </button>
        </form>

        <!-- Divider -->
        <div class="divider">
            <span>or continue with</span>
        </div>

        <!-- Social Login -->
        <div class="social-buttons">
            <button class="social-btn" onclick="window.location.href='#'">
                <i class="ri-google-fill" style="color: #ea4335;"></i>
                Google
            </button>
            <button class="social-btn" onclick="window.location.href='#'">
                <i class="ri-facebook-fill" style="color: #1877f2;"></i>
                Facebook
            </button>
        </div>

        <!-- Register Link -->
        <div class="register-link">
            Don't have an account?
            <a href="{{ route('register') }}">Create one</a>
        </div>

        <!-- Back to Home -->
        <div class="back-home">
            <a href="{{ route('home') }}">
                <i class="ri-arrow-left-line"></i> Back to Home
            </a>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('passwordToggleIcon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('ri-eye-line');
            icon.classList.add('ri-eye-off-line');
        } else {
            input.type = 'password';
            icon.classList.remove('ri-eye-off-line');
            icon.classList.add('ri-eye-line');
        }
    }
</script>
@endsection