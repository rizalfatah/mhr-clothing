@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div
        class="min-h-[80vh] flex flex-col items-center justify-center px-4 py-12 bg-gradient-to-br from-primary-50 to-primary-100">
        <div class="w-full max-w-md">
            <!-- Welcome Message -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary-900 mb-2">Join Us Today!</h1>
                <p class="text-primary-600">Create your account to get started</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-primary-100 overflow-hidden">
                <div class="bg-primary-500 px-8 py-6">
                    <h2 class="text-xl font-semibold text-white">Create Your Account</h2>
                </div>
                <div class="px-8 py-8">
                    <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- name --}}
                        <div>
                            <label for="name" class="block text-sm font-semibold text-primary-700 mb-2">
                                Name
                            </label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900 autofill:bg-white autofill:text-primary-900 autofill:shadow-[inset_0_0_0px_1000px_rgb(255,255,255)] @error('name') border-red-500 @enderror"
                                placeholder="John Doe">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-primary-700 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900 autofill:bg-white autofill:text-primary-900 autofill:shadow-[inset_0_0_0px_1000px_rgb(255,255,255)] @error('email') border-red-500 @enderror"
                                placeholder="customer@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- whatsapp number --}}
                        <div>
                            <label for="whatsapp_number" class="block text-sm font-semibold text-primary-700 mb-2">
                                WhatsApp Number
                            </label>
                            <input type="text" name="whatsapp_number" id="whatsapp_number" required value="{{ old('whatsapp_number') }}"
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900 autofill:bg-white autofill:text-primary-900 autofill:shadow-[inset_0_0_0px_1000px_rgb(255,255,255)] @error('whatsapp_number') border-red-500 @enderror"
                                placeholder="+62812345678">
                            @error('whatsapp_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- password --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-primary-700 mb-2">
                                Password
                            </label>
                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900 autofill:bg-white autofill:text-primary-900 autofill:shadow-[inset_0_0_0px_1000px_rgb(255,255,255)] @error('password') border-red-500 @enderror"
                                placeholder="••••••••">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- confirm password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-primary-700 mb-2">
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full px-4 py-3 rounded-lg border border-primary-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20 transition-colors outline-none text-primary-900 autofill:bg-white autofill:text-primary-900 autofill:shadow-[inset_0_0_0px_1000px_rgb(255,255,255)] @error('password_confirmation') border-red-500 @enderror"
                                placeholder="••••••••">
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- register button --}}
                        <div>
                            <button type="submit"
                                class="w-full bg-primary-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-500 focus:ring-opacity-30 transition-all duration-200 transform hover:scale-[1.02]">
                                Register
                            </button>
                        </div>

                        {{-- Login Link --}}
                        <div class="text-center pt-4">
                            <p class="text-sm text-primary-600">
                                Already have an account?
                                <a href="{{ route('login') }}"
                                    class="font-semibold text-primary-500 hover:text-primary-700 transition-colors">
                                    Login now
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
