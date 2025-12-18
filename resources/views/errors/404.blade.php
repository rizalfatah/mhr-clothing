<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gradient-to-br from-primary-950 via-secondary-900 to-primary-900 min-h-screen flex items-center justify-center font-sans overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div
            class="absolute bottom-0 left-0 w-96 h-96 bg-secondary-500/10 rounded-full blur-3xl animate-pulse delay-700">
        </div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6 py-12 text-center">
        <!-- SVG Illustration -->
        <div class="mb-8 animate-fade-in">
            <svg class="mx-auto w-full max-w-md h-auto" viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg">
                <!-- Lost Person -->
                <g class="animate-bounce" style="animation-duration: 3s;">
                    <!-- Body -->
                    <ellipse cx="250" cy="320" rx="60" ry="15" fill="#101828"
                        opacity="0.3" />
                    <rect x="230" y="260" width="40" height="60" rx="5" fill="#1a1a1a" />
                    <circle cx="250" cy="235" r="25" fill="#1a1a1a" />

                    <!-- Arms -->
                    <path d="M230 270 L210 290 L205 285" stroke="#1a1a1a" stroke-width="8" stroke-linecap="round"
                        fill="none" />
                    <path d="M270 270 L290 290 L295 285" stroke="#1a1a1a" stroke-width="8" stroke-linecap="round"
                        fill="none" />

                    <!-- Legs -->
                    <path d="M235 320 L225 355" stroke="#1a1a1a" stroke-width="8" stroke-linecap="round" />
                    <path d="M265 320 L275 355" stroke="#1a1a1a" stroke-width="8" stroke-linecap="round" />

                    <!-- Face -->
                    <circle cx="240" cy="230" r="3" fill="white" />
                    <circle cx="260" cy="230" r="3" fill="white" />
                    <path d="M240 245 Q250 240 260 245" stroke="white" stroke-width="2" stroke-linecap="round"
                        fill="none" />
                </g>

                <!-- Magnifying Glass -->
                <g class="animate-scale-in" style="animation-delay: 0.3s;">
                    <circle cx="150" cy="150" r="50" fill="none" stroke="#101828" stroke-width="8" />
                    <circle cx="150" cy="150" r="35" fill="rgba(16, 24, 40, 0.1)" />
                    <line x1="185" y1="185" x2="215" y2="215" stroke="#101828" stroke-width="8"
                        stroke-linecap="round" />

                    <!-- Question mark inside glass -->
                    <text x="150" y="165" font-size="40" fill="#1a1a1a" text-anchor="middle" font-weight="bold">?</text>
                </g>

                <!-- Floating 404 Numbers -->
                <g class="animate-slide-in-left">
                    <text x="50" y="80" font-size="72" fill="rgba(26, 26, 26, 0.3)" font-weight="bold">4</text>
                </g>
                <g class="animate-fade-in" style="animation-delay: 0.2s;">
                    <text x="350" y="100" font-size="72" fill="rgba(16, 24, 40, 0.3)" font-weight="bold">0</text>
                </g>
                <g class="animate-slide-in-right" style="animation-delay: 0.4s;">
                    <text x="420" y="160" font-size="72" fill="rgba(26, 26, 26, 0.3)" font-weight="bold">4</text>
                </g>

                <!-- Scattered Dots -->
                <circle cx="100" cy="200" r="4" fill="#1a1a1a" opacity="0.2" class="animate-pulse" />
                <circle cx="380" cy="280" r="4" fill="#101828" opacity="0.2" class="animate-pulse"
                    style="animation-delay: 0.5s;" />
                <circle cx="320" cy="50" r="4" fill="#1a1a1a" opacity="0.2" class="animate-pulse"
                    style="animation-delay: 1s;" />
                <circle cx="450" cy="240" r="4" fill="#101828" opacity="0.2" class="animate-pulse"
                    style="animation-delay: 1.5s;" />
            </svg>
        </div>

        <!-- Error Code -->
        <div class="mb-6 animate-fade-in" style="animation-delay: 0.2s;">
            <h1
                class="text-8xl md:text-9xl font-bold bg-gradient-to-r from-primary-300 via-secondary-300 to-primary-400 bg-clip-text text-transparent mb-2">
                404
            </h1>
            <div
                class="h-1 w-24 mx-auto bg-gradient-to-r from-primary-500 via-secondary-500 to-primary-500 rounded-full">
            </div>
        </div>

        <!-- Error Message -->
        <div class="mb-8 animate-fade-in" style="animation-delay: 0.4s;">
            <h2 class="text-3xl md:text-4xl font-bold text-primary-500 mb-4">
                Oops! Page Not Found
            </h2>
            <p class="text-lg text-gray-300 max-w-md mx-auto leading-relaxed">
                The page you're looking for seems to have wandered off. Let's get you back on track!
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in"
            style="animation-delay: 0.6s;">
            <a href="/"
                class="group relative px-8 py-4 bg-primary-500 text-white rounded-lg font-semibold hover:bg-primary-600 transition-all duration-300 hover:scale-105 hover:shadow-2xl overflow-hidden">
                <span class="relative z-10 flex items-center gap-2">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Back to Home
                </span>
                <div
                    class="absolute inset-0 bg-gradient-to-r from-primary-600 to-secondary-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                </div>
            </a>

            <button onclick="window.history.back()"
                class="group px-8 py-4 bg-transparent border-2 border-secondary-400 text-secondary-200 rounded-lg font-semibold hover:bg-secondary-500  hover:border-secondary-500 transition-all duration-300 hover:scale-105">
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
                Need help?
                <a href="/contact"
                    class="text-secondary-300 hover:text-secondary-200 underline underline-offset-4 transition-colors">
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
