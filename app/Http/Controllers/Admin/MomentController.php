<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MomentCollection;
use App\Models\MomentMedia;
use App\Models\MomentPackage;
use App\Models\MomentSetting;
use App\Services\MomentContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MomentController extends Controller
{
    public static function sections(): array
    {
        return [
            'home' => ['hero', 'bouquet_image', 'budget_initial'],
            'contact' => ['brand_name', 'brand_logo', 'collaboration_logo', 'favicon', 'address', 'maps_url', 'phone', 'whatsapp', 'instagram_url', 'facebook_url', 'threads_url', 'tiktok_url'],
            'policy' => ['booking_template', 'consultation_template', 'terms', 'privacy'],
            'copy' => ['copy'],
        ];
    }

    public function index(Request $r)
    {
        $section = $r->query('tab', 'home');
        abort_unless(in_array($section, [...array_keys(self::sections()), 'packages', 'collections', 'media']), 404);

        return view('admin.moment.index', ['tab' => $section, 'settings' => MomentSetting::findOrFail(1)->data, 'media' => MomentMedia::orderByDesc('id')->get(), 'packages' => MomentPackage::orderBy('sort_order')->get(), 'collections' => MomentCollection::orderBy('sort_order')->get(), 'sections' => self::sections()]);
    }

    public function settings(Request $r, string $section)
    {
        abort_unless(isset(self::sections()[$section]), 404);
        $rules = [];
        $paths = MomentMedia::pluck('path')->all();
        foreach (self::sections()[$section] as $key) {
            $rules[$key] = match ($key) {
                'hero','brand_logo','collaboration_logo' => ['required', Rule::in($paths)],
                'bouquet_image' => ['nullable', Rule::in($paths)],
                'favicon' => ['required', Rule::in([...$paths, 'moment-favicon.svg'])],
                'budget_initial' => ['required', 'integer', 'min:0', 'max:1000000000'],
                'prices_confirmed' => ['required', 'boolean'],
                'whatsapp' => ['required', 'regex:/^62[0-9]{8,13}$/'],
                'maps_url','instagram_url','facebook_url','threads_url','tiktok_url' => ['nullable', 'url:http,https', 'max:2000'],
                'copy' => ['required', 'array'],
                default => ['required', 'string', 'max:10000'],
            };
        }
        $old = MomentSetting::findOrFail(1);
        $data = $old->data;
        if ($section === 'copy') {
            foreach (array_keys($data['copy']) as $key) {
                $rules['copy.'.$key] = ['required', 'string', 'max:10000'];
            }
        }
        $valid = $r->validate($rules);
        if (isset($valid['copy'])) {
            $valid['copy'] = array_intersect_key($valid['copy'], $data['copy']);
        }
        $old->update(['data' => array_replace($data, $valid)]);

        return back()->with('success', 'Konten Moment berhasil disimpan dan langsung ditampilkan.');
    }

    public function save(Request $r, string $type, ?int $id = null)
    {
        abort_unless(in_array($type, ['packages', 'collections']), 404);
        $class = $type === 'packages' ? MomentPackage::class : MomentCollection::class;
        $entry = $id ? $class::findOrFail($id) : new $class;
        $paths = MomentMedia::pluck('path')->all();
        $rules = ['name' => ['required', 'string', 'max:150'], 'image' => ['required', Rule::in($paths)], 'description' => ['required', 'string', 'max:5000'], 'is_active' => ['required', 'boolean'], 'sort_order' => ['required', 'integer', 'min:0', 'max:100000']];
        if ($type === 'packages') {
            $rules += ['price_status' => ['required', Rule::in(['estimate', 'final'])], 'seo_title' => ['nullable', 'string', 'max:200'], 'seo_description' => ['nullable', 'string', 'max:500'], 'title' => ['required', 'string', 'max:150'], 'eyebrow' => ['required', 'string', 'max:255'], 'price' => ['required', 'integer', 'min:0', 'max:1000000000'], 'benefits' => ['required', 'string', 'max:10000'], 'gallery' => ['nullable', 'array', 'max:30'], 'gallery.*' => ['nullable', Rule::in($paths)]];
        } else {
            $rules += ['type' => ['required', Rule::in(['preset', 'design'])], 'style' => ['required', Rule::in(['warm', 'natural', 'editorial', 'chapter'])], 'overlay_top' => ['nullable', 'string', 'max:150'], 'overlay_title' => ['nullable', 'string', 'max:150'], 'overlay_caption' => ['nullable', 'string', 'max:255']];
        }
        $data = $r->validate($rules);
        if (! $id) {
            $slug = Str::slug($data['name']) ?: 'moment';
            $base = $slug;
            $i = 2;
            while (in_array($slug, ['preset-desain', 'admin', 'login', 'catalog', 'cart', 'up']) || $class::where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i++;
            }
            $entry->slug = $slug;
        }
        if ($type === 'packages') {
            $data['benefits'] = array_values(array_filter(array_map('trim', preg_split('/\R/', $data['benefits']))));
            if (! $data['benefits']) {
                throw ValidationException::withMessages(['benefits' => 'Isi minimal satu benefit.']);
            }
            $data['gallery'] = array_values(array_filter($data['gallery'] ?? []));
        } else {
            $data['label'] = $data['type'] === 'preset' ? 'Preset' : 'Desain';
        }
        $entry->fill(['name' => $data['name'], 'is_active' => $data['is_active'], 'sort_order' => $data['sort_order'], 'data' => $data])->save();

        return back()->with('success', 'Konten berhasil disimpan.');
    }

    public function upload(Request $r)
    {
        $data = $r->validate(['image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], 'alt_text' => ['required', 'string', 'max:255']]);
        [$w,$h] = getimagesize($r->file('image')->getRealPath());
        $path = $r->file('image')->store('moment', 'public');
        abort_unless($path, 500, 'Gagal menyimpan gambar.');
        try {
            MomentMedia::create(['path' => $path, 'alt_text' => $data['alt_text'], 'width' => $w, 'height' => $h]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }

        return back()->with('success', 'Gambar berhasil diunggah. Pilih gambar ini pada form konten.');
    }

    public function media(Request $r, MomentMedia $media)
    {
        $media->update($r->validate(['alt_text' => ['required', 'string', 'max:255'], 'focus_x' => ['required', 'integer', 'between:0,100'], 'focus_y' => ['required', 'integer', 'between:0,100']]));

        return back()->with('success', 'Informasi gambar diperbarui.');
    }

    public function destroyMedia(MomentMedia $media)
    {
        if (MomentContent::referenced($media->path)) {
            return back()->withErrors(['media' => 'Gambar masih digunakan. Ganti atau lepaskan dari konten terlebih dahulu.']);
        }
        $path = $media->path;
        $media->delete();
        if (str_starts_with($path, 'moment/')) {
            Storage::disk('public')->delete($path);
        }

        return back()->with('success','Gambar dihapus dari pustaka media.');
    }
}
