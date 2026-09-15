<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        // Group settings by their group
        $settings = Setting::all()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'nullable|array',
            'settings.*' => 'nullable',
            'settings.whatsapp_message_template' => 'sometimes|required|string|max:10000',
            'settings.whatsapp_template_use_header_number' => 'sometimes|boolean',
            'settings.whatsapp_template_number' => 'sometimes|nullable|string|max:20',
            'homepage_banner' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'remove_homepage_banner' => 'nullable|boolean',
        ], [
            'homepage_banner.image' => 'Banner harus berupa gambar.',
            'homepage_banner.mimes' => 'Format banner harus JPG, JPEG, PNG, atau WEBP.',
            'homepage_banner.max' => 'Ukuran banner maksimal 5MB.',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->hasFile('homepage_banner') && $request->boolean('remove_homepage_banner')) {
                $message = 'Unggah banner baru atau pulihkan banner default, bukan keduanya.';
                $validator->errors()->add('homepage_banner', $message);
                $validator->errors()->add('remove_homepage_banner', $message);
            }

            $settings = $request->input('settings', []);
            $usesHeaderNumber = filter_var(
                $settings['whatsapp_template_use_header_number'] ?? true,
                FILTER_VALIDATE_BOOLEAN
            );

            if (! $usesHeaderNumber) {
                $templateNumber = $settings['whatsapp_template_number'] ?? '';

                if ($templateNumber === '') {
                    $validator->errors()->add(
                        'settings.whatsapp_template_number',
                        'Nomor WhatsApp tujuan khusus wajib diisi.'
                    );
                } elseif (! preg_match('/^62[0-9]{8,13}$/', $templateNumber)) {
                    $validator->errors()->add(
                        'settings.whatsapp_template_number',
                        'Nomor WhatsApp harus menggunakan format 62xxxxxxxxxx tanpa tanda + atau spasi.'
                    );
                }
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal. Mohon periksa kembali input Anda.');
        }

        $oldBannerPath = Setting::query()
            ->where('key', 'homepage_banner')
            ->value('value');
        $newBannerPath = null;
        $settingsPersisted = false;
        $removeBanner = $request->boolean('remove_homepage_banner');

        try {
            if ($request->hasFile('homepage_banner')) {
                $newBannerPath = $request->file('homepage_banner')->store('homepage-banners', 'public');
            }

            DB::transaction(function () use ($request, $newBannerPath, $removeBanner) {
                foreach ($request->input('settings', []) as $key => $value) {
                    // The banner path is only managed by the uploaded file flow below.
                    if ($key === 'homepage_banner') {
                        continue;
                    }

                    Setting::where('key', $key)->update([
                        'value' => $value ?? '',
                    ]);
                }

                if ($newBannerPath !== null) {
                    Setting::updateOrCreate(
                        ['key' => 'homepage_banner'],
                        [
                            'value' => $newBannerPath,
                            'type' => 'image',
                            'group' => 'homepage',
                            'description' => 'Banner utama yang ditampilkan di halaman beranda',
                        ]
                    );
                } elseif ($removeBanner) {
                    Setting::where('key', 'homepage_banner')->update(['value' => '']);
                }
            });
            $settingsPersisted = true;

            if (($newBannerPath !== null || $removeBanner) && $this->isManagedHomepageBanner($oldBannerPath)) {
                Storage::disk('public')->delete($oldBannerPath);
            }

            // Clear cache so changes reflect immediately
            Setting::clearCache();

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Pengaturan berhasil disimpan.');
        } catch (\Throwable $e) {
            if ($newBannerPath !== null && ! $settingsPersisted) {
                Storage::disk('public')->delete($newBannerPath);
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    private function isManagedHomepageBanner(?string $path): bool
    {
        return is_string($path) && str_starts_with($path, 'homepage-banners/');
    }
}
