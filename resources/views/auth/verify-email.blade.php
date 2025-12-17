@extends('layouts.app')

@section('title', 'Verify Email Address')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-primary-50 to-primary-100">
        <div class="w-full max-w-md">
            <!-- Welcome Message -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary-900 mb-2">Verify Your Email</h1>
                <p class="text-primary-600">Please verify your email address to continue</p>
            </div>

            <!-- Verification Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-primary-100 overflow-hidden">
                <div class="bg-primary-500 px-8 py-6">
                    <h2 class="text-xl font-semibold text-white">Email Verification</h2>
                </div>

                <div class="px-8 py-8">
                    <!-- Success Message -->
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

                    <!-- Info Message -->
                    @if (session('info'))
                        <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-blue-700">{{ session('info') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div class="flex-1">
                                    @foreach ($errors->all() as $error)
                                        <p class="text-sm text-red-700">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Email Icon -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-100 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-primary-500" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="space-y-4 mb-6">
                        <div class="bg-primary-50 rounded-lg p-4">
                            <h3 class="font-semibold text-primary-900 mb-2 flex items-center">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-primary-500 text-white text-xs font-bold rounded-full mr-2">1</span>
                                Check Your Email
                            </h3>
                            <p class="text-sm text-primary-700">
                                We've sent a verification link to your email address. Please check your inbox and click the
                                link to verify your account.
                            </p>
                        </div>

                        <div class="bg-primary-50 rounded-lg p-4">
                            <h3 class="font-semibold text-primary-900 mb-2 flex items-center">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-primary-500 text-white text-xs font-bold rounded-full mr-2">2</span>
                                Didn't Receive the Email?
                            </h3>
                            <p class="text-sm text-primary-700 mb-3">
                                If you haven't received the email, check your spam folder or click the button below to
                                request a new verification email.
                            </p>

                            <form method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-primary-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-500 focus:ring-opacity-30 transition-all duration-200 transform hover:scale-[1.02]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Resend Verification Email
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Logout Link -->
                    <div class="text-center pt-4 border-t border-primary-100">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-sm text-primary-600 hover:text-primary-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Security Note -->
            <p class="text-center text-xs text-primary-600 mt-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 mr-1" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                Email verification helps keep your account secure
            </p>
        </div>
    </div>
@endsection
