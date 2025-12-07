@extends('layouts.app')

@section('title', 'Account')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-12">
        <!-- Page Title -->
        <h1 class="text-4xl font-bold mb-8">My Account</h1>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login/Signup Banner -->
        <div class="bg-gray-300 rounded-lg p-6 mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold mb-2">Enjoy Special Discounts and Stay Connected</h2>
                <p class="text-gray-700 text-sm">
                    Get access to exclusive discounts while keeping track of your orders and chats with ease.<br>
                    Stay updated on your purchases and engage with us seamlessly, all in one place.
                </p>
            </div>
            <div class="flex gap-3 ml-6">
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-6 py-2 bg-white text-gray-900 font-medium rounded hover:bg-gray-100 transition">
                            Logout
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                        class="px-6 py-2 bg-white text-gray-900 font-medium rounded hover:bg-gray-100 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-6 py-2 bg-black text-white font-medium rounded hover:bg-gray-800 transition">
                        Signup
                    </a>
                @endguest
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="border-2 border-black-400 rounded-lg overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex bg-gray-300">
                <button class="flex-1 py-3 px-6 font-semibold border-b-4 border-black bg-gray-300 transition"
                    id="orders-tab">
                    Orders
                </button>
                <button class="flex-1 py-3 px-6 font-semibold text-gray-500 hover:text-gray-900 transition"
                    id="wishlist-tab">
                    Wishlist
                </button>
                @auth
                    <button class="flex-1 py-3 px-6 font-semibold text-gray-500 hover:text-gray-900 transition"
                        id="profile-tab">
                        Profile
                    </button>
                @endauth
            </div>

            <!-- Tab Content -->
            <div class="bg-gray-300 p-6 min-h-[300px]" id="tab-content">
                <!-- Orders Content -->
                <div id="orders-content">
                    @if ($orders->count() > 0)
                        <div class="grid gap-4">
                            @foreach ($orders as $order)
                                <div class="bg-white rounded-lg border-2 border-gray-400 p-6 hover:shadow-md transition">
                                    <!-- Order Header -->
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="font-bold text-lg mb-1">{{ $order->order_number }}</h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $order->created_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-semibold bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                                            {{ $order->status_label }}
                                        </span>
                                    </div>

                                    <!-- Order Items -->
                                    <div class="border-t border-gray-200 pt-4 mb-4">
                                        <h4 class="font-semibold mb-2 text-sm text-gray-700">Order Items:</h4>
                                        <div class="space-y-2">
                                            @foreach ($order->items as $item)
                                                <div class="flex justify-between items-center text-sm">
                                                    <div class="flex-1">
                                                        <p class="font-medium">{{ $item->product_name }}</p>
                                                        <p class="text-gray-600">Qty: {{ $item->quantity }} × Rp
                                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                                    </div>
                                                    <p class="font-semibold">Rp
                                                        {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="border-t border-gray-200 pt-4">
                                        <div class="flex justify-between items-center mb-2 text-sm">
                                            <span class="text-gray-600">Subtotal</span>
                                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mb-2 text-sm">
                                            <span class="text-gray-600">Shipping Cost</span>
                                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center font-bold text-lg border-t pt-2">
                                            <span>Total</span>
                                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <!-- Shipping Info -->
                                    @if ($order->tracking_number)
                                        <div class="mt-4 p-3 bg-blue-50 rounded border border-blue-200">
                                            <p class="text-sm font-semibold text-blue-900">Tracking Information</p>
                                            <p class="text-sm text-blue-800">{{ $order->courier }}:
                                                {{ $order->tracking_number }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 text-center py-12">No orders yet</p>
                    @endif
                </div>

                <!-- Wishlist Content (Hidden by default) -->
                <div id="wishlist-content" class="hidden">
                    <p class="text-gray-600 text-center py-12">Your wishlist is empty</p>
                </div>

                <!-- Profile Content (Hidden by default) -->
                @auth
                    <div id="profile-content" class="hidden">
                        <div class="space-y-6">
                            <!-- Personal Information Section -->
                            <div class="bg-white rounded-lg border-2 border-gray-400 p-6">
                                <h3 class="text-xl font-bold mb-4">Personal Information</h3>
                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-semibold mb-2">Name</label>
                                            <input type="text" name="name"
                                                value="{{ old('name', auth()->user()->name) }}"
                                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2">Email</label>
                                            <input type="email" name="email"
                                                value="{{ old('email', auth()->user()->email) }}"
                                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                                required>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-semibold mb-2">WhatsApp Number</label>
                                            <input type="text" name="whatsapp_number"
                                                value="{{ old('whatsapp_number', auth()->user()->whatsapp_number) }}"
                                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                                required>
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="px-6 py-2 bg-black text-white font-medium rounded hover:bg-gray-800 transition">
                                        Update Information
                                    </button>
                                </form>
                            </div>

                            <!-- Change Password Section -->
                            <div class="bg-white rounded-lg border-2 border-gray-400 p-6">
                                <h3 class="text-xl font-bold mb-4">Change Password</h3>
                                <form action="{{ route('profile.password') }}" method="POST">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <label class="block text-sm font-semibold mb-2">Current Password</label>
                                            <input type="password" name="current_password"
                                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2">New Password</label>
                                            <input type="password" name="password"
                                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2">Confirm New Password</label>
                                            <input type="password" name="password_confirmation"
                                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                                required>
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="px-6 py-2 bg-black text-white font-medium rounded hover:bg-gray-800 transition">
                                        Change Password
                                    </button>
                                </form>
                            </div>

                            <!-- Addresses Section -->
                            <div class="bg-white rounded-lg border-2 border-gray-400 p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xl font-bold">My Addresses</h3>
                                    <button onclick="openAddressModal()"
                                        class="px-4 py-2 bg-black text-white text-sm font-medium rounded hover:bg-gray-800 transition">
                                        Add New Address
                                    </button>
                                </div>

                                @if ($addresses->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($addresses as $address)
                                            <div class="border-2 border-gray-400 rounded p-4 relative">
                                                @if ($address->is_default)
                                                    <span
                                                        class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded">Default</span>
                                                @endif
                                                <div class="mb-3">
                                                    @if ($address->label)
                                                        <p class="font-bold text-lg">{{ $address->label }}</p>
                                                    @endif
                                                    <p class="font-semibold">{{ $address->recipient_name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $address->phone_number }}</p>
                                                </div>
                                                <div class="mb-3 text-sm">
                                                    <p>{{ $address->address_line_1 }}</p>
                                                    @if ($address->address_line_2)
                                                        <p>{{ $address->address_line_2 }}</p>
                                                    @endif
                                                    <p>{{ $address->city }}, {{ $address->province }}
                                                        {{ $address->postal_code }}</p>
                                                    @if ($address->notes)
                                                        <p class="text-gray-600 italic mt-1">Note: {{ $address->notes }}</p>
                                                    @endif
                                                </div>
                                                <div class="flex gap-2">
                                                    <button onclick="editAddress({{ $address->id }})"
                                                        class="text-sm text-blue-600 hover:underline">Edit</button>
                                                    @if (!$address->is_default)
                                                        <form action="{{ route('addresses.setDefault', $address) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit"
                                                                class="text-sm text-green-600 hover:underline">Set as
                                                                Default</button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('addresses.destroy', $address) }}" method="POST"
                                                        class="inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this address?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-sm text-red-600 hover:underline">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-600 text-center py-8">No addresses saved yet</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Address Modal -->
    @auth
        <div id="addressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold" id="modalTitle">Add New Address</h3>
                    <button onclick="closeAddressModal()" class="text-gray-600 hover:text-black text-2xl">×</button>
                </div>
                <form id="addressForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold mb-2">Label (Optional)</label>
                            <input type="text" name="label" id="label" placeholder="e.g. Home, Office"
                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Recipient Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="recipient_name" id="recipient_name"
                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Phone Number <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="phone_number" id="phone_number"
                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold mb-2">Address Line 1 <span
                                    class="text-red-500">*</span></label>
                            <textarea name="address_line_1" id="address_line_1" rows="2"
                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none" required></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold mb-2">Address Line 2 (Optional)</label>
                            <textarea name="address_line_2" id="address_line_2" rows="2"
                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">City <span class="text-red-500">*</span></label>
                            <input type="text" name="city" id="city"
                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Province <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="province" id="province"
                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Postal Code <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="postal_code" id="postal_code"
                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"
                                required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold mb-2">Delivery Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="w-full border-2 border-gray-400 rounded px-3 py-2 focus:border-black focus:outline-none"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_default" id="is_default" value="1"
                                    class="mr-2 w-4 h-4">
                                <span class="text-sm font-semibold">Set as default address</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                            class="px-6 py-2 bg-black text-white font-medium rounded hover:bg-gray-800 transition">
                            Save Address
                        </button>
                        <button type="button" onclick="closeAddressModal()"
                            class="px-6 py-2 bg-gray-300 text-gray-900 font-medium rounded hover:bg-gray-400 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endauth

    @push('scripts')
        <script>
            const ordersTab = document.getElementById('orders-tab');
            const wishlistTab = document.getElementById('wishlist-tab');
            const ordersContent = document.getElementById('orders-content');
            const wishlistContent = document.getElementById('wishlist-content');

            @auth
            const profileTab = document.getElementById('profile-tab');
            const profileContent = document.getElementById('profile-content');

            // Address data for editing
            const addresses = @json($addresses);
            @endauth

            function switchTab(activeTab, activeContent) {
                // Remove active styles from all tabs
                [ordersTab, wishlistTab @auth, profileTab
                @endauth ].forEach(tab => {
                tab.classList.remove('border-b-4', 'border-black', 'bg-gray-300');
                tab.classList.add('text-gray-500');
            });

            // Add active styles to selected tab
            activeTab.classList.add('border-b-4', 'border-black', 'bg-gray-300');
            activeTab.classList.remove('text-gray-500');

            // Hide all content
            [ordersContent, wishlistContent @auth, profileContent
            @endauth ].forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected content
            activeContent.classList.remove('hidden');
            }

            ordersTab.addEventListener('click', function() {
                switchTab(ordersTab, ordersContent);
            });

            wishlistTab.addEventListener('click', function() {
                switchTab(wishlistTab, wishlistContent);
            });

            @auth
            profileTab.addEventListener('click', function() {
                switchTab(profileTab, profileContent);
            });

            // Address Modal Functions
            function openAddressModal(addressId = null) {
                const modal = document.getElementById('addressModal');
                const form = document.getElementById('addressForm');
                const modalTitle = document.getElementById('modalTitle');
                const formMethod = document.getElementById('formMethod');

                if (addressId) {
                    // Edit mode
                    const address = addresses.find(a => a.id === addressId);
                    if (!address) return;

                    modalTitle.textContent = 'Edit Address';
                    form.action = `/addresses/${addressId}`;
                    formMethod.value = 'PUT';

                    // Fill form with existing data
                    document.getElementById('label').value = address.label || '';
                    document.getElementById('recipient_name').value = address.recipient_name;
                    document.getElementById('phone_number').value = address.phone_number;
                    document.getElementById('address_line_1').value = address.address_line_1;
                    document.getElementById('address_line_2').value = address.address_line_2 || '';
                    document.getElementById('city').value = address.city;
                    document.getElementById('province').value = address.province;
                    document.getElementById('postal_code').value = address.postal_code;
                    document.getElementById('notes').value = address.notes || '';
                    document.getElementById('is_default').checked = address.is_default;
                } else {
                    // Add mode
                    modalTitle.textContent = 'Add New Address';
                    form.action = '/addresses';
                    formMethod.value = 'POST';
                    form.reset();
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeAddressModal() {
                const modal = document.getElementById('addressModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function editAddress(addressId) {
                openAddressModal(addressId);
            }

            // Close modal when clicking outside
            document.getElementById('addressModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddressModal();
                }
            });
            @endauth
        </script>
    @endpush
@endsection
