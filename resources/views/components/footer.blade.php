<footer class="bg-primary-500 text-primary-300 mt-auto">
    <!-- Main Footer Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            <!-- About Section -->
            <div class="space-y-4">
                <h3 class="text-white text-lg font-bold mb-4">MHR Clothing</h3>
                <p class="text-sm leading-relaxed">
                    Your destination for premium streetwear and contemporary fashion. Quality clothing that defines your
                    style.
                </p>
                <!-- Social Media Links -->
                <div class="flex gap-4 pt-2">
                    <a href="#"
                        class="w-9 h-9 bg-primary-600 hover:bg-primary-700 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110"
                        aria-label="Facebook">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                        </svg>
                    </a>
                    <a href="#"
                        class="w-9 h-9 bg-primary-600 hover:bg-primary-700 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110"
                        aria-label="Instagram">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M16 2H8a6 6 0 0 0-6 6v8a6 6 0 0 0 6 6h8a6 6 0 0 0 6-6V8a6 6 0 0 0-6-6zm-4 13a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm4.5-7.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-4">
                <h3 class="text-white text-lg font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}"
                            class="hover:text-white hover:pl-2 transition-all duration-200">Home</a></li>
                    <li><a href="{{ route('catalog') }}"
                            class="hover:text-white hover:pl-2 transition-all duration-200">Shop</a></li>
                    <li><a href="#" class="hover:text-white hover:pl-2 transition-all duration-200">About </a>
                    </li>

                </ul>
            </div>

            <!-- Customer Service -->
            <div class="space-y-4">
                <h3 class="text-white text-lg font-bold mb-4">Customer Service</h3>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#" class="hover:text-white hover:pl-2 transition-all duration-200">My Account</a>
                    </li>
                    <li><a href="#" class="hover:text-white hover:pl-2 transition-all duration-200">Shipping
                            Info</a></li>
                </ul>
            </div>

            <!-- Payment Methods -->
            <div class="space-y-4">
                <h3 class="text-white text-lg font-bold mb-4">Payment Methods</h3>
                <div class="space-y-3">
                    <!-- Banks Row -->
                    <div>
                        <p class="text-xs mb-2">Bank Transfer</p>
                        <div class="flex flex-wrap gap-2">
                            <div class="w-14 h-9 bg-white rounded-md flex items-center justify-center p-1.5 shadow-sm hover:shadow-md transition-shadow"
                                title="BCA">
                                <img src="{{ asset('icon-bank/BCA.svg') }}" alt="BCA"
                                    class="w-full h-full object-contain">
                            </div>
                            <div class="w-14 h-9 bg-white rounded-md flex items-center justify-center p-1.5 shadow-sm hover:shadow-md transition-shadow"
                                title="BNI">
                                <img src="{{ asset('icon-bank/BNI.svg') }}" alt="BNI"
                                    class="w-full h-full object-contain">
                            </div>
                            <div class="w-14 h-9 bg-white rounded-md flex items-center justify-center p-1.5 shadow-sm hover:shadow-md transition-shadow"
                                title="BRI">
                                <img src="{{ asset('icon-bank/BRI.svg') }}" alt="BRI"
                                    class="w-full h-full object-contain">
                            </div>
                            <div class="w-14 h-9 bg-white rounded-md flex items-center justify-center p-1.5 shadow-sm hover:shadow-md transition-shadow"
                                title="Mandiri">
                                <img src="{{ asset('icon-bank/mandiri.svg') }}" alt="Mandiri"
                                    class="w-full h-full object-contain">
                            </div>
                        </div>
                    </div>
                    <!-- E-Wallets & QRIS Row -->
                    <div>
                        <p class="text-xs mb-2">E-Wallet & QRIS</p>
                        <div class="flex flex-wrap gap-2">
                            <div class="w-14 h-9 bg-white rounded-md flex items-center justify-center p-1.5 shadow-sm hover:shadow-md transition-shadow"
                                title="GoPay">
                                <img src="{{ asset('icon-bank/gopay.svg') }}" alt="GoPay"
                                    class="w-full h-full object-contain">
                            </div>
                            <div class="w-14 h-9 bg-white rounded-md flex items-center justify-center p-1.5 shadow-sm hover:shadow-md transition-shadow"
                                title="ShopeePay">
                                <img src="{{ asset('icon-bank/shopee_pay.svg') }}" alt="ShopeePay"
                                    class="w-full h-full object-contain">
                            </div>
                            <div class="w-14 h-9 bg-white rounded-md flex items-center justify-center p-1.5 shadow-sm hover:shadow-md transition-shadow"
                                title="QRIS">
                                <img src="{{ asset('icon-bank/QRIS.svg') }}" alt="QRIS"
                                    class="w-full h-full object-contain">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-primary-600">
        <div class="container mx-auto px-4 py-5">
            <div class="flex justify-center items-center text-sm">
                <p class="">&copy; {{ date('Y') }} MHR Clothing. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
