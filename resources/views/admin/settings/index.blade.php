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
                        <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-800 dark:text-white capitalize">
                            @switch($group)
                                @case('general')
                                    <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <rect width="14" height="17" x="5" y="3" rx="2" />
                                        <path d="M9 3v2M15 3v2M9 12h6M9 16h4" />
                                    </svg>
                                    <span>Pengaturan Umum</span>
                                @break

                                @case('whatsapp')
                                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" role="img"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <title>WhatsApp icon</title>
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                    </svg>
                                    <span>Pengaturan WhatsApp</span>
                                @break

                                @case('contact')
                                    <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path
                                            d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .8 2.9a2 2 0 0 1-.5 2.1L8.1 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.4 1.9.7 2.9.8a2 2 0 0 1 1.6 1.9z" />
                                    </svg>
                                    <span>Informasi Kontak</span>
                                @break

                                @case('shipping')
                                    <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="M10 17h4V5H2v12h3M14 8h4l4 4v5h-2M14 17h1M6 17h1" />
                                        <circle cx="7.5" cy="17.5" r="2.5" />
                                        <circle cx="17.5" cy="17.5" r="2.5" />
                                    </svg>
                                    <span>Pengaturan Pengiriman</span>
                                @break

                                @case('promotional')
                                    <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10" />
                                        <circle cx="12" cy="12" r="6" />
                                        <circle cx="12" cy="12" r="2" />
                                    </svg>
                                    <span>Pengaturan Promosi</span>
                                @break

                                @case('homepage')
                                    <svg class="size-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" aria-hidden="true">
                                        <path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z" />
                                        <path d="M9 21v-6h6v6" />
                                    </svg>
                                    <span>Banner Halaman Beranda</span>
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
                                                ? \Illuminate\Support\Facades\Storage::disk('public')->url(
                                                    $setting->value,
                                                )
                                                : asset('images/banner.webp');
                                        @endphp

                                        <img src="{{ $bannerUrl }}" alt="Pratinjau banner halaman beranda"
                                            class="mb-4 h-40 w-full rounded-lg border border-gray-200 object-cover dark:border-neutral-700">

                                        <input type="file" id="setting_{{ $setting->key }}" name="homepage_banner"
                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                            class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4 dark:file:bg-neutral-700 dark:file:text-neutral-400 @error('homepage_banner') border-red-500 @enderror">
                                        <p class="mt-2 text-xs text-gray-500 dark:text-neutral-400">JPG, JPEG, PNG, atau
                                            WEBP, maksimal 5MB.</p>

                                        @if ($hasCustomBanner)
                                            <label class="mt-3 inline-flex items-center">
                                                <input type="checkbox" name="remove_homepage_banner" value="1"
                                                    class="shrink-0 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700">
                                                <span class="text-sm text-gray-500 ms-2 dark:text-neutral-400">Pulihkan
                                                    banner bawaan</span>
                                            </label>
                                        @endif

                                        @error('homepage_banner')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                        @error('remove_homepage_banner')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    @elseif ($setting->type === 'textarea' || $setting->key === 'whatsapp_message_template')
                                        <textarea id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]"
                                            rows="{{ $setting->key === 'whatsapp_message_template' ? 14 : 4 }}"
                                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 {{ $setting->key === 'whatsapp_message_template' ? 'font-mono' : '' }}"
                                            placeholder="{{ $setting->description }}">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>

                                        @if ($setting->key === 'whatsapp_message_template')
                                            <div
                                                class="mt-3 rounded-lg bg-gray-50 p-3 text-xs text-gray-600 dark:bg-neutral-900 dark:text-neutral-400">
                                                <p class="font-medium text-gray-700 dark:text-neutral-300">Placeholder yang
                                                    tersedia</p>
                                                <p class="mt-1 leading-5"><code>{admin_name}</code>,
                                                    <code>{order_number}</code>, <code>{order_items}</code>,
                                                    <code>{subtotal}</code>, <code>{discount}</code>,
                                                    <code>{discount_line}</code>, <code>{shipping_cost}</code>,
                                                    <code>{total}</code>
                                                </p>
                                                <p class="leading-5"><code>{customer_name}</code>,
                                                    <code>{customer_whatsapp}</code>, <code>{customer_email}</code>,
                                                    <code>{customer_email_line}</code>, <code>{shipping_address}</code>,
                                                    <code>{shipping_city}</code>, <code>{shipping_province}</code>,
                                                    <code>{shipping_postal_code}</code>,
                                                    <code>{shipping_postal_code_line}</code>, <code>{shipping_notes}</code>,
                                                    <code>{shipping_notes_line}</code>
                                                </p>
                                            </div>
                                        @endif
                                    @elseif ($setting->key === 'whatsapp_template_use_header_number')
                                        @php
                                            $useHeaderNumber =
                                                old('settings.whatsapp_template_use_header_number', $setting->value) ==
                                                '1';
                                        @endphp
                                        <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                        <label class="inline-flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox" id="setting_{{ $setting->key }}"
                                                name="settings[{{ $setting->key }}]" value="1"
                                                {{ $useHeaderNumber ? 'checked' : '' }}
                                                class="shrink-0 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700">
                                            <span class="text-sm text-gray-600 dark:text-neutral-400">Gunakan nomor
                                                WhatsApp yang tampil di header sebagai tujuan pesan checkout</span>
                                        </label>
                                    @elseif ($setting->key === 'whatsapp_template_number')
                                        @php
                                            $useHeaderNumber =
                                                old(
                                                    'settings.whatsapp_template_use_header_number',
                                                    \App\Models\Setting::get(
                                                        'whatsapp_template_use_header_number',
                                                        true,
                                                    ),
                                                ) == '1';
                                        @endphp
                                        <div id="whatsapp-template-number-field"
                                            class="{{ $useHeaderNumber ? 'hidden' : '' }}">
                                            <input type="text" id="setting_{{ $setting->key }}"
                                                name="settings[{{ $setting->key }}]"
                                                value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                                {{ $useHeaderNumber ? 'disabled' : '' }} inputmode="numeric"
                                                autocomplete="tel"
                                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('settings.' . $setting->key) border-red-500 @enderror"
                                                placeholder="628xxxxxxxxxx">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-neutral-500">Format:
                                                628xxxxxxxxxx (tanpa tanda + atau spasi).</p>
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
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                    Reset
                </button>
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
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
        document.addEventListener('DOMContentLoaded', function() {
            const useHeaderCheckbox = document.getElementById('setting_whatsapp_template_use_header_number');
            const customNumberField = document.getElementById('whatsapp-template-number-field');
            const customNumberInput = document.getElementById('setting_whatsapp_template_number');

            if (!useHeaderCheckbox || !customNumberField || !customNumberInput) {
                return;
            }

            const toggleCustomNumberField = function() {
                const useHeaderNumber = useHeaderCheckbox.checked;
                customNumberField.classList.toggle('hidden', useHeaderNumber);
                customNumberInput.disabled = useHeaderNumber;
            };

            useHeaderCheckbox.addEventListener('change', toggleCustomNumberField);
            toggleCustomNumberField();
        });
    </script>
@endsection
