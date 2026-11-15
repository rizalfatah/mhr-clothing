<?php

namespace App\Services;

use App\Models\MomentCollection;
use App\Models\MomentMedia;
use App\Models\MomentPackage;
use App\Models\MomentSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MomentContent
{
    public static function defaults(): array
    {
        $data = config('moment');
        unset($data['packages'],$data['presets'],$data['domain'],$data['store_domain']);

        return [...$data, 'brand_name' => 'Sepulang Moment', 'phone' => $data['whatsapp'], 'maps_url' => '', 'favicon' => 'moment-favicon.svg', 'bouquet_image' => '', 'budget_initial' => 300000, 'facebook_url' => '', 'threads_url' => '', 'tiktok_url' => '',
            'booking_template' => 'Halo Sepulang Moment, saya tertarik dengan paket {package_name}. Boleh info harga dan jadwal foto wisuda?',
            'consultation_template' => 'Halo Sepulang Moment, saya ingin konsultasi foto wisuda.', 'terms' => 'Hubungi tim Sepulang Moment untuk ketentuan pemesanan, jadwal, pembayaran, dan penyerahan foto.', 'privacy' => 'Hubungi tim Sepulang Moment untuk informasi penggunaan data pemesanan dan izin publikasi foto.', 'copy' => require resource_path('moment-copy.php')];
    }

    public static function initialize(): void
    {
        DB::transaction(function () {
            MomentSetting::firstOrCreate(['id' => 1], ['data' => self::defaults()]);
            $setting = MomentSetting::findOrFail(1);
            $setting->update(['data' => array_replace_recursive(self::defaults(), $setting->data)]);
            foreach (config('moment.packages') as $slug => $data) {
                MomentPackage::firstOrCreate(['slug' => $slug], ['name' => $data['name'], 'data' => $data, 'sort_order' => array_search($slug, array_keys(config('moment.packages')))]);
            }
            foreach (config('moment.presets') as $i => $data) {
                MomentCollection::firstOrCreate(['slug' => Str::slug($data['name'])], ['name' => $data['name'], 'data' => $data, 'sort_order' => $i]);
            }
            foreach (glob(public_path('images/moment/*/*.webp')) as $file) {
                [$w,$h] = getimagesize($file);
                $path = str_replace(public_path().DIRECTORY_SEPARATOR, '', $file);
                $package = collect(config('moment.packages'))->firstWhere('image', $path);
                preg_match('/(\d+)% (\d+)%/', $package['position'] ?? '50% 50%', $focus);
                MomentMedia::firstOrCreate(['path' => $path], ['alt_text' => $package['image_alt'] ?? str_replace('-', ' ', pathinfo($file, PATHINFO_FILENAME)), 'width' => $w, 'height' => $h, 'focus_x' => $focus[1], 'focus_y' => $focus[2]]);
            }
        });
    }

    public function read(): array
    {
        $stored = MomentSetting::findOrFail(1)->data;
        $editable = ['hero', 'address', 'maps_url', 'phone', 'whatsapp', 'instagram_url', 'facebook_url', 'threads_url', 'tiktok_url'];
        $data = array_replace(self::defaults(), array_intersect_key($stored, array_flip($editable)));
        $media = MomentMedia::all()->keyBy('path');
        $packages = [];
        foreach (MomentPackage::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get() as $p) {
            $image = $media->get($p->data['image']);
            $packages[$p->slug] = [...$p->data, 'name' => $p->name, 'slug' => $p->slug, 'title' => Str::replaceFirst('Moment ', '', $p->name), 'eyebrow' => config('moment.packages.'.$p->slug.'.eyebrow', 'Abadikan cerita wisudamu.'), 'seo_title' => '', 'seo_description' => '', 'prices_confirmed' => isset($p->data['price_status']) ? $p->data['price_status'] === 'final' : $data['prices_confirmed'], 'image_alt' => $image?->alt_text ?? $p->name, 'position' => ($image?->focus_x ?? 50).'% '.($image?->focus_y ?? 50).'%'];
        }
        $data['packages'] = $packages;
        $data['prices_confirmed'] = count($packages) > 0 && collect($packages)->every(fn ($p) => $p['prices_confirmed']);
        $data['mediaPositions'] = $media->map(fn ($m) => $m->focus_x.'% '.$m->focus_y.'%')->all();
        $data['hero_alt'] = $media->get($data['hero'])?->alt_text ?? $data['brand_name'];
        $data['presets'] = MomentCollection::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()->map(fn ($p) => [...array_diff_key($p->data, array_flip(['overlay_top', 'overlay_title', 'overlay_caption'])), 'name' => $p->name])->all();
        $data['whatsappUrl'] = 'https://wa.me/'.$data['whatsapp'];
        $data['mapsUrl'] = $data['maps_url'] ?: 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($data['address']);
        $data['budget_min'] = count($packages) ? min(array_column($packages, 'price')) : 0;
        $data['budget_max'] = max($data['budget_min'] + 50000, count($packages) ? max(array_column($packages, 'price')) : 50000);
        $data['budget_initial'] = max($data['budget_min'], min($data['budget_max'], $data['budget_initial']));

        return $data;
    }

    public static function imageUrl(string $path): string
    {
        return asset(str_starts_with($path, 'moment/') ? 'storage/'.$path : $path);
    }

    public static function referenced(string $path): bool
    {
        foreach ([MomentSetting::findOrFail(1)->data, ...MomentPackage::pluck('data')->all(), ...MomentCollection::pluck('data')->all()] as $data) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($data));
            foreach ($iterator as $value) {
                if ($value === $path) {
                    return true;
                }
            }
        }

        return false;
    }
}
