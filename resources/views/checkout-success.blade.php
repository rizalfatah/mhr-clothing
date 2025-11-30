@extends('layouts.app')

@section('title', 'Order Successful')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <!-- Success Icon -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Successfully Created!</h1>
                    <p class="text-gray-600">Thank you for your order</p>
                </div>

                <!-- Order Details Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 md:p-8 mb-6">
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Order Details</h2>
                        <p class="text-sm text-gray-500 mt-1">Order Number: <span
                                class="font-medium text-gray-900">{{ $order->order_number }}</span></p>
                    </div>

                    <!-- Products -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Ordered Products</h3>
                        <div class="space-y-3">
                            @foreach ($order->items as $item)
                                <div class="flex gap-4 pb-3 border-b border-gray-100 last:border-0">
                                    @if ($item->product && $item->product->images->first())
                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                            alt="{{ $item->product_name }}" class="w-20 h-20 object-cover rounded">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                        <p class="text-sm font-medium text-gray-900 mt-1">Rp
                                            {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Summary -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium">
                                    @if ($order->shipping_cost == 0)
                                        <span class="text-green-600">FREE</span>
                                    @else
                                        Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold pt-2 border-t border-gray-200">
                                <span>Total</span>
                                <span class="text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="border-t border-gray-200 mt-6 pt-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Shipping Information</h3>
                        <div class="grid md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Recipient Name</p>
                                <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">WhatsApp</p>
                                <p class="font-medium text-gray-900">{{ $order->customer_whatsapp }}</p>
                            </div>
                            @if ($order->customer_email)
                                <div>
                                    <p class="text-gray-600">Email</p>
                                    <p class="font-medium text-gray-900">{{ $order->customer_email }}</p>
                                </div>
                            @endif
                            <div class="md:col-span-2">
                                <p class="text-gray-600">Shipping Address</p>
                                <p class="font-medium text-gray-900">{{ $order->shipping_address }}</p>
                                <p class="font-medium text-gray-900">{{ $order->shipping_city }},
                                    {{ $order->shipping_province }}
                                    @if ($order->shipping_postal_code)
                                        {{ $order->shipping_postal_code }}
                                    @endif
                                </p>
                            </div>
                            @if ($order->shipping_notes)
                                <div class="md:col-span-2">
                                    <p class="text-gray-600">Notes</p>
                                    <p class="font-medium text-gray-900">{{ $order->shipping_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- WhatsApp CTA -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-8 text-center mb-6">
                    <div class="flex justify-center mb-4">
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Confirm Order via WhatsApp</h2>
                    <p class="text-green-50 mb-6">Click the button below to contact admin and confirm your
                        order
                    </p>
                    <a href="{{ $whatsappUrl }}" target="_blank"
                        class="inline-flex items-center gap-3 bg-white text-green-600 px-8 py-4 rounded-lg font-semibold hover:bg-green-50 transition shadow-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                        Contact Admin on WhatsApp
                    </a>
                    <p class="text-green-50 text-sm mt-4">Your order details are automatically arranged in the WhatsApp
                        message
                    </p>
                </div>

                <!-- Additional Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <div class="flex gap-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-blue-900 mb-2">Next Steps</h3>
                            <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                                <li>Click the "Contact Admin on WhatsApp" button to confirm order</li>
                                <li>Admin will reply with payment details and availability confirmation</li>
                                <li>Make payment according to admin's instructions</li>
                                <li>Send transfer proof to admin</li>
                                <li>Your order will be processed immediately after payment is confirmed</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('catalog') }}"
                        class="flex-1 text-center bg-gray-900 text-white py-4 rounded-lg font-semibold hover:bg-gray-800 transition">
                        Continue Shopping
                    </a>
                    <a href="{{ route('home') }}"
                        class="flex-1 text-center bg-white border-2 border-gray-300 text-gray-900 py-4 rounded-lg font-semibold hover:bg-gray-50 transition">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-open WhatsApp after 2 seconds
            setTimeout(function() {
                const whatsappUrl = "{{ $whatsappUrl }}";
                if (whatsappUrl) {
                    // Uncomment the line below to auto-open WhatsApp
                    // window.open(whatsappUrl, '_blank');
                }
            }, 2000);
        </script>
    @endpush
@endsection
