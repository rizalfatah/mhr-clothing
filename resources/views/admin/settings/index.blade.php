@extends('admin.layouts.app')

@section('title', 'Pengaturan')
@section('breadcrumb', 'Pengaturan')

@section('content')
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Pengaturan Aplikasi</h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">Kelola pengaturan aplikasi dan konfigurasi sistem</p>
    </div>

    <!-- Settings Form -->
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            @foreach ($settings as $group => $groupSettings)
                <!-- Settings Card -->
                <div
                    class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                    <!-- Card Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white capitalize">
                            @switch($group)
                                @case('general')
                                    📋 Pengaturan Umum
                                @break

                                @case('whatsapp')
                                    💬 Pengaturan WhatsApp
                                @break

                                @case('contact')
                                    📞 Informasi Kontak
                                @break

                                @case('shipping')
                                    🚚 Pengaturan Pengiriman
                                @break

                                @case('promotional')
                                    🎯 Pengaturan Promosi
                                @break

                                @case('homepage')
                                    🏠 Banner Halaman Beranda
                                @break

                                @default
                                    {{ ucfirst($group) }}
                            @endswitch
                        </h2>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($groupSettings as $setting)
                                <div>
                                    <label for="setting_{{ $setting->key }}"
                                        class="block text-sm font-medium mb-2 text-gray-700 dark:text-neutral-300">
                                        {{ $setting->description }}
                                    </label>

                                    @if ($setting->type === 'image')
                                        @php
                                            $hasCustomBanner = filled($setting->value);
                                            $bannerUrl = $hasCustomBanner
                                                ? \Illuminate\Support\Facades\Storage::disk('public')->url($setting->value)
                                                : asset('images/banner.webp');
                                        @endphp

                                        <img src="{{ $bannerUrl }}" alt="Pratinjau banner halaman beranda"
                                            class="mb-4 h-40 w-full rounded-lg border border-gray-200 object-cover dark:border-neutral-700">

                                        <input type="file" id="setting_{{ $setting->key }}" name="homepage_banner"
                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                            class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4 dark:file:bg-neutral-700 dark:file:text-neutral-400 @error('homepage_banner') border-red-500 @enderror">
                                        <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">JPG, JPEG, PNG, atau WEBP, maksimal 5MB.</p>

                                        @if ($hasCustomBanner)
                                            <label class="mt-3 inline-flex items-center">
                                                <input type="checkbox" name="remove_homepage_banner" value="1"
                                                    class="shrink-0 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700">
                                                <span class="text-sm text-gray-500 ms-2 dark:text-neutral-400">Pulihkan banner bawaan</span>
                                            </label>
                                        @endif

                                        @error('homepage_banner')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                        @error('remove_homepage_banner')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    @elseif ($setting->key === 'whatsapp_message_template')
                                        <textarea id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="14"
                                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm font-mono focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                            placeholder="{{ $setting->description }}">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                                        <div class="mt-3 rounded-lg bg-gray-50 p-3 text-xs text-gray-600 dark:bg-neutral-900 dark:text-neutral-400">
                                            <p class="font-medium text-gray-700 dark:text-neutral-300">Placeholder yang tersedia</p>
                                            <p class="mt-1 leading-5"><code>{admin_name}</code>, <code>{order_number}</code>, <code>{order_items}</code>, <code>{subtotal}</code>, <code>{discount}</code>, <code>{discount_line}</code>, <code>{shipping_cost}</code>, <code>{total}</code></p>
                                            <p class="leading-5"><code>{customer_name}</code>, <code>{customer_whatsapp}</code>, <code>{customer_email}</code>, <code>{customer_email_line}</code>, <code>{shipping_address}</code>, <code>{shipping_city}</code>, <code>{shipping_province}</code>, <code>{shipping_postal_code}</code>, <code>{shipping_postal_code_line}</code>, <code>{shipping_notes}</code>, <code>{shipping_notes_line}</code></p>
                                        </div>
                                    @elseif ($setting->key === 'whatsapp_template_use_header_number')
                                        @php
                                            $useHeaderNumber = old('settings.whatsapp_template_use_header_number', $setting->value) == '1';
                                        @endphp
                                        <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                        <label class="inline-flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox" id="setting_{{ $setting->key }}"
                                                name="settings[{{ $setting->key }}]" value="1"
                                                {{ $useHeaderNumber ? 'checked' : '' }}
                                                class="shrink-0 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700">
                                            <span class="text-sm text-gray-600 dark:text-neutral-400">Gunakan nomor WhatsApp yang tampil di header sebagai tujuan pesan checkout</span>
                                        </label>
                                    @elseif ($setting->key === 'whatsapp_template_number')
                                        @php
                                            $useHeaderNumber = old('settings.whatsapp_template_use_header_number', \App\Models\Setting::get('whatsapp_template_use_header_number', true)) == '1';
                                        @endphp
                                        <div id="whatsapp-template-number-field" class="{{ $useHeaderNumber ? 'hidden' : '' }}">
                                            <input type="text" id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]"
                                                value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                                {{ $useHeaderNumber ? 'disabled' : '' }}
                                                inputmode="numeric" autocomplete="tel"
                                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('settings.' . $setting->key) border-red-500 @enderror"
                                                placeholder="628xxxxxxxxxx">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-neutral-500">Format: 628xxxxxxxxxx (tanpa tanda + atau spasi).</p>
                                        </div>
                                    @elseif ($setting->type === 'boolean')
                                        <!-- Toggle Switch for Boolean -->
                                        <div class="flex items-center">
                                            <!-- Hidden input to ensure value is sent even when unchecked -->
                                            <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                            <input type="checkbox" id="setting_{{ $setting->key }}"
                                                name="settings[{{ $setting->key }}]" value="1"
                                                {{ old('settings.' . $setting->key, $setting->value) == '1' ? 'checked' : '' }}
                                                class="relative w-11 h-6 bg-gray-100 border-transparent text-blue-600 rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-blue-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-blue-600 checked:border-blue-600 focus:checked:border-blue-600 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-600

                                                before:inline-block before:size-5 before:bg-white checked:before:bg-white before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-white">
                                            <label for="setting_{{ $setting->key }}"
                                                class="text-sm text-gray-500 ms-3 dark:text-neutral-400">
                                                {{ old('settings.' . $setting->key, $setting->value) == '1' ? 'Aktif' : 'Tidak Aktif' }}
                                            </label>
                                        </div>
                                    @else
                                        <!-- Regular Input -->
                                        <input type="{{ $setting->type === 'number' ? 'number' : 'text' }}"
                                            id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]"
                                            value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                            placeholder="{{ $setting->description }}"
                                            @if ($setting->type === 'number') step="1" min="0" @endif>
                                    @endif

                                    @if ($setting->key === 'whatsapp_admin_number')
                                        <p class="text-xs text-gray-500 mt-1 dark:text-neutral-500">
                                            Format: 628xxxxxxxxxx (tanpa tanda + atau spasi)
                                        </p>
                                    @endif

                                    @error('settings.' . $setting->key)
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Action Buttons -->
            <div class="flex justify-end gap-x-2">
                <button type="button"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                    onclick="window.location.reload()">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                    Reset
                </button>
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const useHeaderCheckbox = document.getElementById('setting_whatsapp_template_use_header_number');
            const customNumberField = document.getElementById('whatsapp-template-number-field');
            const customNumberInput = document.getElementById('setting_whatsapp_template_number');

            if (!useHeaderCheckbox || !customNumberField || !customNumberInput) {
                return;
            }

            const toggleCustomNumberField = function () {
                const useHeaderNumber = useHeaderCheckbox.checked;
                customNumberField.classList.toggle('hidden', useHeaderNumber);
                customNumberInput.disabled = useHeaderNumber;
            };

            useHeaderCheckbox.addEventListener('change', toggleCustomNumberField);
            toggleCustomNumberField();
        });
    </script>
@endsection
