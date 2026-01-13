@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-primary-50 to-primary-100">
        <div class="w-full max-w-md">
            <!-- Welcome Message -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary-900 mb-2">Welcome Back!</h1>
                <p class="text-primary-600">Sign in to continue your best fashion shopping</p>
            </div>

            <!-- Login Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-primary-100 overflow-hidden">
                <div class="bg-primary-500 px-8 py-6">
                    <h2 class="text-xl font-semibold text-white">Login to Your Account</h2>
                </div>

                <div class="px-8 py-8">
                    <!-- Session Expired Alert -->
                    @if (session('error'))
                        <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-yellow-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Success Alert -->
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Email Not Verified Section -->
                    @if (session('email_not_verified'))
                        <div class="mb-6 space-y-4">
                            <!-- Error Message -->
                            @if ($errors->has('email'))
                                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="text-sm text-red-700">{{ $errors->first('email') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Email Icon -->
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-500"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Verification Instructions -->
                            <div class="bg-primary-50 rounded-lg p-4">
                                <h3 class="font-semibold text-primary-900 mb-2 flex items-center">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-primary-500 text-white text-xs font-bold rounded-full mr-2">!</span>
                                    Email Verification Required
                                </h3>
                                <p class="text-sm text-primary-700 mb-3">
                                    We've sent a verification link to your email address. Please check your inbox and click
                                    the
                                    link to verify your account before logging in.
                                </p>

                                <form method="POST" action="{{ route('verification.resend.guest') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-primary-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-500 focus:ring-opacity-30 transition-all duration-200 transform hover:scale-[1.02]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Resend Verification Email
                                    </button>
                                </form>
                            </div>

                            <p class="text-center text-xs text-primary-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                Check your spam folder if you don't see the email
                            </p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.auth') }}" class="space-y-6" id="loginForm">
                        @csrf

                        <!-- Email or WhatsApp Field -->
                        <div>
                            <label for="login" class="block text-sm font-semibold text-primary-700 mb-2">
                                Email or WhatsApp Number
                            </label>
                            <input type="text" name="login" id="login" required value="{{ old('login') }}"
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2                                focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900                                 autofill:bg-white autofill:text-primary-900 autofill:shadow-[inset_0_0_0px_1000px_rgb(255,255,255)] @error('login') border-red-500 @enderror"
                                placeholder="customer@example.com or +62812345678">
                            @error('login')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-primary-700 mb-2">
                                Password
                            </label>
                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900 autofill:bg-white autofill:text-primary-900 autofill:shadow-[inset_0_0_0px_1000px_rgb(255,255,255)]"
                                placeholder="••••••••">
                        </div>

                        <!-- Remember & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 accent-primary-500 border-primary-300 rounded focus:ring-primary-500">
                                <span class="ml-2 text-sm text-primary-600">Remember me</span>
                            </label>
                            <a href="#"
                                class="text-sm font-medium text-primary-500 hover:text-primary-700 transition-colors">
                                Forgot password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full bg-primary-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-500 focus:ring-opacity-30 transition-all duration-200 transform hover:scale-[1.02]">
                            Sign In
                        </button>

                        <!-- Register Link -->
                        <div class="text-center pt-4">
                            <p class="text-sm text-primary-600">
                                Don't have an account?
                                <a href="{{ route('register') }}"
                                    class="font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                                    Register now
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <p class="text-center text-xs text-primary-500 mt-6">
                By signing in, you agree to our <a href="#" class="text-primary-500 hover:underline">Terms &
                    Conditions</a>
                and <a href="#" class="text-primary-500 hover:underline">Privacy Policy</a>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-refresh CSRF token every 60 minutes to prevent expiration
        // Session lifetime is 120 minutes, so this keeps token fresh
        setInterval(function() {
            fetch('{{ route('login') }}', {
                    method: 'GET',
                    credentials: 'same-origin'
                })
                .then(response => response.text())
                .then(html => {
                    // Extract new CSRF token from response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newToken = doc.querySelector('input[name="_token"]');

                    if (newToken) {
                        // Update CSRF token in the form
                        const currentToken = document.querySelector('input[name="_token"]');
                        if (currentToken) {
                            currentToken.value = newToken.value;
                            console.log('CSRF token refreshed');
                        }
                    }
                })
                .catch(error => console.log('Token refresh failed:', error));
        }, 60 * 60 * 1000); // 60 minutes in milliseconds
    </script>
@endpush
