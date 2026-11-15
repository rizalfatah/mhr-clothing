<?php

use App\Http\Middleware\TrackUserSession;
use App\Models\MomentCollection;
use App\Models\MomentMedia;
use App\Models\MomentPackage;
use App\Models\MomentSetting;
use App\Models\User;
use App\Services\MomentContent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    config(['session.driver' => 'array', 'cache.default' => 'array']);
});
test('only administrators can manage Moment content', function () {
    $this->get(route('admin.moment.index'))->assertRedirect(route('login'));
    $this->actingAs(User::factory()->create(['role' => 'customer']))->get(route('admin.moment.index'))->assertForbidden();
});
test('all Moment admin tabs render for an administrator', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    foreach (['home', 'packages', 'collections', 'contact', 'policy', 'copy', 'media'] as $tab) {
        $this->get(route('admin.moment.index', ['tab' => $tab]))->assertOk()->assertSee('Konten Moment');
    }
});
test('contact updates appear immediately on the Moment domain', function () {
    $data = MomentSetting::find(1)->data;
    $this->actingAs(User::factory()->create(['role' => 'admin']))->put(route('admin.moment.settings', 'contact'), array_merge(array_intersect_key($data, array_flip(['brand_name', 'brand_logo', 'collaboration_logo', 'favicon', 'address', 'maps_url', 'phone', 'whatsapp', 'instagram_url'])), ['address' => 'Alamat baru', 'whatsapp' => '6281234567890']))->assertSessionHasNoErrors();
    $this->withoutMiddleware(TrackUserSession::class)->get('https://'.config('moment.domain').'/')->assertOk()->assertSee('Alamat baru')->assertSee('https://wa.me/6281234567890', false);
});
test('an administrator can create a dynamic package and deactivate it', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $data = ['name' => 'Paket Baru', 'title' => 'Baru', 'description' => 'Cerita baru', 'eyebrow' => 'Untukmu', 'price' => 789000, 'price_status' => 'final', 'image' => MomentMedia::first()->path, 'benefits' => "Sesi foto\nHasil edit", 'is_active' => 1, 'sort_order' => 4];
    $this->post(route('admin.moment.store', 'packages'), $data)->assertSessionHasNoErrors();
    $package = MomentPackage::where('slug', 'paket-baru')->firstOrFail();
    $this->withoutMiddleware(TrackUserSession::class)->get('https://'.config('moment.domain').'/paket-baru')->assertOk()->assertSee('789.000');
    $this->put('http://localhost'.route('admin.moment.update', ['packages', $package->id], false), [...$data, 'is_active' => 0])->assertSessionHasNoErrors();
    $this->get('https://'.config('moment.domain').'/paket-baru')->assertNotFound();
});
test('uploads are validated and referenced media cannot be deleted', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $this->post(route('admin.moment.media.store'), ['image' => UploadedFile::fake()->create('bad.svg', 1, 'image/svg+xml'), 'alt_text' => 'Bad'])->assertSessionHasErrors('image');
    $this->post(route('admin.moment.media.store'), ['image' => UploadedFile::fake()->image('photo.png'), 'alt_text' => 'Foto baru'])->assertSessionHasNoErrors();
    $media = MomentMedia::where('alt_text', 'Foto baru')->firstOrFail();
    Storage::disk('public')->assertExists($media->path);
    $this->delete(route('admin.moment.media.destroy', MomentMedia::where('path', MomentSetting::find(1)->data['hero'])->first()))->assertSessionHasErrors('media');
    $this->delete(route('admin.moment.media.destroy', $media))->assertSessionHasNoErrors();
    Storage::disk('public')->assertMissing($media->path);
});
test('initialisation preserves edits and an empty catalogue renders gracefully', function () {
    $s = MomentSetting::find(1);
    $s->update(['data' => [...$s->data, 'brand_name' => 'Brand baru']]);
    MomentContent::initialize();
    expect(MomentSetting::find(1)->data['brand_name'])->toBe('Brand baru');
    MomentPackage::query()->update(['is_active' => false]);
    $this->get('https://'.config('moment.domain').'/')->assertOk()->assertDontSee('data-package-carousel', false)->assertSee('Konsultasikan paket');
});

test('collections support editing order and visibility', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $data = ['name' => 'Koleksi Baru', 'description' => 'Warna baru', 'image' => MomentMedia::first()->path, 'type' => 'preset', 'style' => 'natural', 'is_active' => 1, 'sort_order' => 0];
    $this->post(route('admin.moment.store', 'collections'), $data)->assertRedirect()->assertSessionHasNoErrors();
    $entry = MomentCollection::where('slug', 'koleksi-baru')->firstOrFail();
    $this->put(route('admin.moment.update', ['collections', $entry->id]), [...$data, 'is_active' => 0])->assertRedirect()->assertSessionHasNoErrors();
    $this->get('https://'.config('moment.domain').'/preset-desain')->assertOk()->assertDontSee('Koleksi Baru');
});

test('uploaded media resolves on the Moment hostname and policy text is escaped', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $this->post(route('admin.moment.media.store'), ['image' => UploadedFile::fake()->image('hero.png'), 'alt_text' => 'Hero baru']);
    $image = MomentMedia::where('alt_text', 'Hero baru')->firstOrFail();
    $this->put(route('admin.moment.settings', 'home'), ['hero' => $image->path, 'bouquet_image' => '', 'budget_initial' => 300000])->assertSessionHasNoErrors();
    $s = MomentSetting::find(1);
    $s->update(['data' => [...$s->data, 'privacy' => '</script><script>alert(1)</script>']]);
    $this->withoutMiddleware(TrackUserSession::class)->get('https://'.config('moment.domain').'/')->assertOk()->assertSee('https://'.config('moment.domain').'/storage/'.$image->path, false)->assertDontSee('</script><script>alert(1)</script>', false);
});

test('invalid package edits leave stored prices and identity unchanged', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));
    $p = MomentPackage::where('slug', 'portrait')->firstOrFail();
    $this->put(route('admin.moment.update', ['packages', $p->id]), ['name' => 'Changed', 'price' => -1])->assertSessionHasErrors();
    expect($p->fresh()->name)->toBe($p->name)->and($p->fresh()->data['price'])->toBe($p->data['price']);
});
