@extends('layouts.app')

@section('title', 'Complete Your Admin Registration')

@section('content')
    <div
        class="min-h-[80vh] flex flex-col items-center justify-center px-4 py-12 bg-gradient-to-br from-primary-50 to-primary-100">
        <div class="w-full max-w-md">
            <!-- Welcome Message -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary-900 mb-2">Welcome to MHR Admin!</h1>
                <p class="text-primary-600">Complete your registration to get started</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-primary-100 overflow-hidden">
                <div class="bg-primary-500 px-8 py-6">
                    <h2 class="text-xl font-semibold text-white">Complete Your Profile</h2>
                    <p class="text-primary-100 text-sm mt-1">You've been invited as an administrator</p>
                </div>
                <div class="px-8 py-8">
                    <form action="{{ route('admin.invite.accept.store', $invite->token) }}" method="POST"
                        class="space-y-6">
                        @csrf

                        {{-- Email (readonly) --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-primary-700 mb-2">
                                Email
                            </label>
                            <input type="email" id="email" value="{{ $invite->email }}" readonly
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500">Email address from your invitation</p>
                        </div>

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-semibold text-primary-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900 @error('name') border-red-500 @enderror"
                                placeholder="John Doe">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- WhatsApp Number --}}
                        <div>
                            <label for="whatsapp_number" class="block text-sm font-semibold text-primary-700 mb-2">
                                WhatsApp Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="whatsapp_number" id="whatsapp_number" required
                                value="{{ old('whatsapp_number') }}"
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900 @error('whatsapp_number') border-red-500 @enderror"
                                placeholder="+62812345678">
                            @error('whatsapp_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-primary-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900 @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-primary-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900"
                                placeholder="••••••••">
                        </div>

                        {{-- Submit Button --}}
                        <div>
                            <button type="submit"
                                class="w-full bg-primary-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-500 focus:ring-opacity-30 transition-all duration-200 transform hover:scale-[1.02]">
                                Complete Registration
                            </button>
                        </div>

                        {{-- Expiration Notice --}}
                        <div class="text-center pt-2">
                            <p class="text-xs text-gray-500">
                                This invitation expires on {{ $invite->expires_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
