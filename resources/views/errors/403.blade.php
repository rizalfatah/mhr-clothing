<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gradient-to-br from-secondary-950 via-primary-900 to-secondary-900 min-h-screen flex items-center justify-center font-sans overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-secondary-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div
            class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl animate-pulse delay-700">
        </div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 py-12 text-center">
        <!-- SVG Illustration -->
        <div class="mb-8 animate-fade-in">
            <svg class="mx-auto w-full max-w-md h-auto" viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg">
                <!-- Lock Illustration -->
                <g class="animate-scale-in">
                    <!-- Lock Body -->
                    <rect x="175" y="200" width="150" height="140" rx="15" fill="#1a1a1a" />
                    <rect x="180" y="205" width="140" height="130" rx="12" fill="#101828" />

                    <!-- Lock Shackle -->
                    <path d="M200 200 V150 A50 50 0 0 1 300 150 V200" stroke="#1a1a1a" stroke-width="25" fill="none"
                        stroke-linecap="round" class="animate-pulse" />

                    <!-- Keyhole -->
                    <circle cx="250" cy="260" r="18" fill="#1a1a1a" />
                    <rect x="242" y="260" width="16" height="35" rx="3" fill="#1a1a1a" />

                    <!-- Shine effect on lock -->
                    <path d="M190 220 L200 240 L195 250" stroke="rgba(255, 255, 255, 0.2)" stroke-width="3"
                        stroke-linecap="round" fill="none" />
                </g>

                <!-- Warning Signs -->
                <g class="animate-slide-in-left">
                    <!-- Left Warning Triangle -->
                    <path d="M80 180 L110 240 L50 240 Z" fill="none" stroke="#1a1a1a" stroke-width="4" />
                    <text x="80" y="225" font-size="24" fill="#1a1a1a" text-anchor="middle" font-weight="bold">!</text>
                </g>

                <g class="animate-slide-in-right">
                    <!-- Right Warning Triangle -->
                    <path d="M420 180 L450 240 L390 240 Z" fill="none" stroke="#101828" stroke-width="4" />
                    <text x="420" y="225" font-size="24" fill="#101828" text-anchor="middle" font-weight="bold">!</text>
                </g>

                <!-- Security Shield Icons -->
                <g class="animate-fade-in" style="animation-delay: 0.3s; opacity: 0.2;">
                    <path d="M120 80 L120 40 L150 30 L180 40 L180 80 Q180 110 150 120 Q120 110 120 80 Z"
                        fill="#1a1a1a" />
                </g>
                <g class="animate-fade-in" style="animation-delay: 0.5s; opacity: 0.2;">
                    <path d="M320 300 L320 340 L350 350 L380 340 L380 300 Q380 270 350 260 Q320 270 320 300 Z"
                        fill="#101828" />
                </g>

                <!-- Prohibition Sign -->
                <g class="animate-scale-in" style="animation-delay: 0.4s;">
                    <circle cx="400" cy="100" r="35" fill="none" stroke="#1a1a1a" stroke-width="6"
                        opacity="0.3" />
                    <line x1="375" y1="125" x2="425" y2="75" stroke="#1a1a1a" stroke-width="6"
                        opacity="0.3" />
                </g>

                <!-- Floating Particles -->
                <circle cx="100" cy="150" r="3" fill="#1a1a1a" opacity="0.3" class="animate-pulse" />
                <circle cx="380" cy="180" r="3" fill="#101828" opacity="0.3" class="animate-pulse"
                    style="animation-delay: 0.5s;" />
                <circle cx="140" cy="280" r="3" fill="#1a1a1a" opacity="0.3" class="animate-pulse"
                    style="animation-delay: 1s;" />
                <circle cx="360" cy="250" r="3" fill="#101828" opacity="0.3" class="animate-pulse"
                    style="animation-delay: 1.5s;" />
                <circle cx="250" cy="50" r="3" fill="#1a1a1a" opacity="0.3" class="animate-pulse"
                    style="animation-delay: 0.7s;" />
            </svg>
        </div>

        <!-- Error Code -->
        <div class="mb-6 animate-fade-in" style="animation-delay: 0.2s;">
            <h1
                class="text-8xl md:text-9xl font-bold bg-gradient-to-r from-secondary-300 via-primary-300 to-secondary-400 bg-clip-text text-transparent mb-2">
                403
            </h1>
            <div
                class="h-1 w-24 mx-auto bg-gradient-to-r from-secondary-500 via-primary-500 to-secondary-500 rounded-full">
            </div>
        </div>

        <!-- Error Message -->
        <div class="mb-8 animate-fade-in" style="animation-delay: 0.4s;">
            <h2 class="text-3xl md:text-4xl font-bold text-primary-500 mb-4">
                Access Forbidden
            </h2>
            <p class="text-lg text-gray-300 max-w-md mx-auto leading-relaxed">
                You don't have permission to access this resource. This area is restricted.
            </p>
        </div>

        <!-- Info Box -->
        {{-- <div class="mb-8 max-w-lg mx-auto animate-fade-in" style="animation-delay: 0.5s;">
            <div class="bg-secondary-800/30 backdrop-blur-sm border border-secondary-600/30 rounded-xl p-6">
                <h3 class="text-primary-500 font-semibold mb-3 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    Why am I seeing this?
                </h3>
                <ul class="text-sm text-gray-400 space-y-2 text-left">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>You may not be logged in or verified</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Your account may not have sufficient privileges</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>This page may be for administrators only</span>
                    </li>
                </ul>
            </div>
        </div> --}}

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in"
            style="animation-delay: 0.6s;">
            @auth
                <a href="/"
                    class="group relative px-8 py-4 bg-secondary-500 text-primary-500 rounded-lg font-semibold hover:bg-secondary-600 transition-all duration-300 hover:scale-105 hover:shadow-2xl overflow-hidden">
                    <span class="relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Back to Home
                    </span>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-secondary-600 to-primary-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                </a>
            @else
                <a href="/login"
                    class="group relative px-8 py-4 bg-secondary-500 text-white rounded-lg font-semibold hover:bg-secondary-600 transition-all duration-300 hover:scale-105 hover:shadow-2xl overflow-hidden">
                    <span class="relative z-10 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Sign In
                    </span>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-secondary-600 to-primary-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                </a>
            @endauth

            <button onclick="window.history.back()"
                class="group px-8 py-4 bg-transparent border-2 border-primary-400 text-primary-200 rounded-lg font-semibold hover:text-primary-500 hover:border-primary-500 transition-all duration-300 hover:scale-105">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Go Back
                </span>
            </button>
        </div>

        <!-- Additional Help -->
        {{-- <div class="mt-12 animate-fade-in" style="animation-delay: 0.8s;">
            <p class="text-sm text-gray-400">
                Think this is a mistake?
                <a href="/contact"
                    class="text-primary-300 hover:text-primary-200 underline underline-offset-4 transition-colors">
                    Contact Support
                </a>
            </p>
        </div> --}}
    </div>

    <style>
        @keyframes delay-700 {

            0%,
            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .delay-700 {
            animation-delay: 0.7s;
        }
    </style>
</body>

</html>
