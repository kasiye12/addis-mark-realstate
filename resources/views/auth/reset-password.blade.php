@extends('layouts.frontend')

@section('title', 'Reset Password - ' . setting('site_name', 'Addis Mark Real Estate'))

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    .auth-bg { background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); }
</style>
@endpush

@section('content')
<div class="auth-bg min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-shield-keyhole-line text-3xl text-blue-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
                    <p class="text-gray-600 mt-1">Enter your new password</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <i class="ri-mail-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="email" name="email" value="{{ old('email', $request->email) }}" required readonly
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="relative">
                            <i class="ri-lock-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password" required
                                   class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror"
                                   placeholder="••••••••">
                            <button type="button" onclick="togglePassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <i class="ri-lock-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password_confirmation" required
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="ri-check-line"></i>
                        Reset Password
                    </button>
                </form>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 text-sm inline-flex items-center gap-1 transition">
                    <i class="ri-arrow-left-line"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(button) {
        const input = button.parentElement.querySelector('input');
        const icon = button.querySelector('i');
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