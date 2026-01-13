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
