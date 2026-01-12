@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Checkout</h1>
                <p class="text-gray-600">Complete your shipping information</p>
            </div>

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf

                <!-- Validation Errors Summary -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-red-800 mb-2">
                                    There are {{ $errors->count() }} errors in the form:
                                </h3>
                                <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($stockAdjustments) && !empty($stockAdjustments))
                    <div class="mb-6 bg-orange-50 border-l-4 border-orange-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-orange-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-orange-800 mb-2">
                                    Cart quantities have been adjusted:
                                </h3>
                                <ul class="space-y-1 text-sm text-orange-700">
                                    @foreach ($stockAdjustments as $adjustment)
                                        <li>
                                            @if (isset($adjustment['removed']) && $adjustment['removed'])
                                                <strong>{{ $adjustment['product'] }}</strong>
                                                @if ($adjustment['size'])
                                                    (Size: {{ $adjustment['size'] }})
                                                @endif
                                                - <span class="font-medium">Removed (out of stock)</span>
                                            @else
                                                <strong>{{ $adjustment['product'] }}</strong>
                                                @if ($adjustment['size'])
                                                    (Size: {{ $adjustment['size'] }})
                                                @endif
                                                - Quantity adjusted from <span
                                                    class="font-medium">{{ $adjustment['old_quantity'] }}</span>
                                                to <span class="font-medium">{{ $adjustment['new_quantity'] }}</span>
                                                (only {{ $adjustment['new_quantity'] }} available)
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Left Column: Forms -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Customer Information -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="customer_name" name="customer_name"
                                        value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('customer_name') border-red-500 @enderror">
                                    @error('customer_name')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                                        WhatsApp Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="customer_whatsapp" name="customer_whatsapp"
                                        value="{{ old('customer_whatsapp', auth()->check() ? auth()->user()->whatsapp_number : '') }}"
                                        placeholder="example: 081234567890" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('customer_whatsapp') border-red-500 @enderror">
                                    @error('customer_whatsapp')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">WhatsApp number will be used for order
                                        confirmation</p>
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email (Optional)
                                    </label>
                                    <input type="email" id="customer_email" name="customer_email"
                                        value="{{ old('customer_email', auth()->check() ? auth()->user()->email : '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('customer_email') border-red-500 @enderror">
                                    @error('customer_email')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Address</h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Full Address <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="shipping_address" name="shipping_address" rows="3" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_address') border-red-500 @enderror">{{ old('shipping_address', auth()->check() && auth()->user()->addresses()->where('is_default', true)->first() ? trim((auth()->user()->addresses()->where('is_default', true)->first()->address_line_1 ?? '') . "\n" . (auth()->user()->addresses()->where('is_default', true)->first()->address_line_2 ?? '')) : '') }}</textarea>
                                    @error('shipping_address')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-2">
                                            City/Regency <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="shipping_city" name="shipping_city"
                                            value="{{ old('shipping_city', auth()->check() && auth()->user()->addresses()->where('is_default', true)->first() ? auth()->user()->addresses()->where('is_default', true)->first()->city : '') }}"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_city') border-red-500 @enderror">
                                        @error('shipping_city')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="shipping_province" class="block text-sm font-medium text-gray-700 mb-2">
                                            Province <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="shipping_province" name="shipping_province"
                                            value="{{ old('shipping_province', auth()->check() && auth()->user()->addresses()->where('is_default', true)->first() ? auth()->user()->addresses()->where('is_default', true)->first()->province : '') }}"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_province') border-red-500 @enderror">
                                        @error('shipping_province')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                        Postal Code (Optional)
                                    </label>
                                    <input type="text" id="shipping_postal_code" name="shipping_postal_code"
                                        value="{{ old('shipping_postal_code', auth()->check() && auth()->user()->addresses()->where('is_default', true)->first() ? auth()->user()->addresses()->where('is_default', true)->first()->postal_code : '') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_postal_code') border-red-500 @enderror">
                                    @error('shipping_postal_code')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Shipping Notes (Optional)
                                    </label>
                                    <textarea id="shipping_notes" name="shipping_notes" rows="2"
                                        placeholder="Example: Please contact before delivery"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('shipping_notes') border-red-500 @enderror">{{ old('shipping_notes', auth()->check() && auth()->user()->addresses()->where('is_default', true)->first() ? auth()->user()->addresses()->where('is_default', true)->first()->notes : '') }}</textarea>
                                    @error('shipping_notes')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm p-6 sticky top-36">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>

                            <!-- Cart Items -->
                            <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                                @foreach ($cartItems as $item)
                                    <div class="flex gap-3 pb-3 border-b border-gray-100">
                                        @if ($item['product']->images->first())
                                            <img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}"
                                                alt="{{ $item['product']->name }}"
                                                class="w-16 h-16 object-cover rounded">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No Image</span>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-900 truncate">
                                                {{ $item['product']->name }}
                                            </h3>
                                            @if (isset($item['variant_size']) && $item['variant_size'])
                                                <p class="text-xs text-gray-600 mt-0.5">
                                                    Size: {{ $item['variant_size'] }}
                                                </p>
                                            @endif
                                            <p class="text-sm text-gray-500">
                                                {{ $item['quantity'] }} x Rp
                                                {{ number_format($item['product']->price, 0, ',', '.') }}
                                            </p>
                                            <p class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Coupon Section -->
                            <div class="mb-4 pt-4 border-t border-gray-200">
                                <label for="coupon_code" class="block text-sm font-medium text-gray-700 mb-2">Coupon
                                    Code</label>
                                @if (isset($appliedCoupon) && $appliedCoupon)
                                    <div
                                        class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium text-green-800">{{ $appliedCoupon->code }}</p>
                                            <p class="text-xs text-green-600">
                                                {{ $appliedCoupon->type == 'fixed' ? 'Discount Rp ' . number_format($appliedCoupon->value, 0, ',', '.') : 'Discount ' . $appliedCoupon->value . '%' }}
                                            </p>
                                        </div>
                                        <button type="button" onclick="removeCoupon()"
                                            class="text-red-500 hover:text-red-700 p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    <!-- Remove Coupon Message Container -->
                                    <div id="remove-coupon-message" class="mt-2 hidden"></div>
                                @else
                                    <div class="flex space-x-2">
                                        <input type="text" id="coupon_code" placeholder="Enter code"
                                            class="flex-1 min-w-0 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                        <button type="button" onclick="applyCoupon()"
                                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                                            Apply
                                        </button>
                                    </div>
                                    <!-- Coupon Message Container -->
                                    <div id="coupon-message" class="mt-2 hidden"></div>
                                @endif
                            </div>

                            <!-- Price Summary -->
                            <div class="space-y-2 pt-4 border-t border-gray-200">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>

                                @if (isset($discountAmount) && $discountAmount > 0)
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>Diskon</span>
                                        <span class="font-medium">-Rp
                                            {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium">
                                        @if ($shippingCost == 0)
                                            <span class="text-green-600">FREE</span>
                                        @else
                                            Rp {{ number_format($shippingCost, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200">
                                    <span>Total</span>
                                    <span class="text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @push('scripts')
                                <script>
                                    function showCouponMessage(message, type) {
                                        const messageContainer = document.getElementById('coupon-message');
                                        if (!messageContainer) return;

                                        const isSuccess = type === 'success';
                                        messageContainer.className = `mt-2 p-3 rounded-lg text-sm ${
                                            isSuccess 
                                                ? 'bg-green-50 border border-green-200 text-green-800' 
                                                : 'bg-red-50 border border-red-200 text-red-800'
                                        }`;
                                        messageContainer.textContent = message;
                                        messageContainer.classList.remove('hidden');
                                    }

                                    function hideCouponMessage() {
                                        const messageContainer = document.getElementById('coupon-message');
                                        if (messageContainer) {
                                            messageContainer.classList.add('hidden');
                                        }
                                    }

                                    function applyCoupon() {
                                        const code = document.getElementById('coupon_code').value;
                                        if (!code) {
                                            showCouponMessage('Please enter a coupon code', 'error');
                                            return;
                                        }

                                        hideCouponMessage();

                                        fetch('{{ route('checkout.coupon.apply') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({
                                                    code: code
                                                })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    showCouponMessage(data.message, 'success');
                                                    setTimeout(() => location.reload(), 1000);
                                                } else {
                                                    showCouponMessage(data.message, 'error');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                showCouponMessage('An error occurred while processing the coupon.', 'error');
                                            });
                                    }

                                    function showRemoveCouponMessage(message, type) {
                                        const messageContainer = document.getElementById('remove-coupon-message');
                                        if (!messageContainer) return;

                                        const isSuccess = type === 'success';
                                        messageContainer.className = `mt-2 p-3 rounded-lg text-sm ${
                                            isSuccess 
                                                ? 'bg-green-50 border border-green-200 text-green-800' 
                                                : 'bg-red-50 border border-red-200 text-red-800'
                                        }`;
                                        messageContainer.textContent = message;
                                        messageContainer.classList.remove('hidden');
                                    }

                                    function removeCoupon() {
                                        if (!confirm('Are you sure you want to remove this coupon?')) return;

                                        fetch('{{ route('checkout.coupon.remove') }}', {
                                                method: 'DELETE',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    showRemoveCouponMessage(data.message, 'success');
                                                    setTimeout(() => location.reload(), 1000);
                                                } else {
                                                    showRemoveCouponMessage(data.message, 'error');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                showRemoveCouponMessage('An error occurred while removing the coupon.', 'error');
                                            });
                                    }
                                </script>
                            @endpush
                            <!-- WhatsApp Info -->
                            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-green-900">Confirmation via WhatsApp</p>
                                        <p class="text-xs text-green-700 mt-1">After checkout, you will be directed to
                                            admin's WhatsApp for order confirmation</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full mt-6 bg-gray-900 text-white py-4 rounded-lg font-semibold hover:bg-gray-800 transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                Continue to WhatsApp
                            </button>

                            <a href="{{ route('catalog') }}"
                                class="block w-full mt-3 text-center py-3 text-gray-600 hover:text-gray-900 transition">
                                &larr; Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
