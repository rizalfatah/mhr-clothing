<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Create an admin user
    $this->admin = User::factory()->create([
        'email' => 'admin@test.com',
        'role' => 'admin',
    ]);
});

test('admin can access settings page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.settings.index');
    $response->assertViewHas('settings');
});

test('non-admin cannot access settings page', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->get(route('admin.settings.index'));

    $response->assertStatus(403);
});

test('guest cannot access settings page', function () {
    $response = $this->get(route('admin.settings.index'));

    $response->assertRedirect(route('login'));
});

test('admin can update settings', function () {
    Setting::factory()->create([
        'key' => 'site_name',
        'value' => 'Old Name',
        'type' => 'text',
        'group' => 'general',
    ]);

    $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
        'settings' => [
            'site_name' => 'New Name',
        ],
    ]);

    $response->assertRedirect(route('admin.settings.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('settings', [
        'key' => 'site_name',
        'value' => 'New Name',
    ]);
});

test('admin can update whatsapp admin number', function () {
    Setting::factory()->create([
        'key' => 'whatsapp_admin_number',
        'value' => '6281234567890',
        'type' => 'text',
        'group' => 'whatsapp',
    ]);

    $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
        'settings' => [
            'whatsapp_admin_number' => '6289876543210',
        ],
    ]);

    $response->assertRedirect(route('admin.settings.index'));

    $this->assertDatabaseHas('settings', [
        'key' => 'whatsapp_admin_number',
        'value' => '6289876543210',
    ]);
});

test('settings are grouped by category', function () {
    Setting::factory()->create(['key' => 'site_name', 'group' => 'general']);
    Setting::factory()->create(['key' => 'whatsapp_admin_number', 'group' => 'whatsapp']);
    Setting::factory()->create(['key' => 'shipping_cost', 'group' => 'shipping']);

    $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));

    $response->assertStatus(200);
    $settings = $response->viewData('settings');

    expect($settings->keys())->toContain('general', 'whatsapp', 'shipping');
});

test('guest cannot update homepage banner', function () {
    $response = $this->put(route('admin.settings.update'), [
        'settings' => [],
    ]);

    $response->assertRedirect(route('login'));
});

test('customer cannot update homepage banner', function () {
    $customer = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($customer)->put(route('admin.settings.update'), [
        'settings' => [],
    ]);

    $response->assertForbidden();
});

test('settings page shows custom homepage banner preview', function () {
    Storage::fake('public');
    Storage::disk('public')->put('homepage-banners/current.jpg', 'banner');
    Setting::updateOrCreate(
        ['key' => 'homepage_banner'],
        [
            'value' => 'homepage-banners/current.jpg',
            'type' => 'image',
            'group' => 'homepage',
            'description' => 'Banner utama yang ditampilkan di halaman beranda',
        ]
    );

    $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));

    $response->assertOk();
    $response->assertSee(Storage::disk('public')->url('homepage-banners/current.jpg'), false);
    $response->assertSee('Pulihkan banner bawaan');
});

test('settings page shows fallback banner preview when no custom banner exists', function () {
    Setting::updateOrCreate(
        ['key' => 'homepage_banner'],
        [
            'value' => '',
            'type' => 'image',
            'group' => 'homepage',
            'description' => 'Banner utama yang ditampilkan di halaman beranda',
        ]
    );

    $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));

    $response->assertOk();
    $response->assertSee(asset('images/banner.webp'), false);
    $response->assertDontSee('Pulihkan banner bawaan');
});

test('admin can replace the homepage banner and refresh the cached homepage setting', function () {
    Storage::fake('public');
    $oldPath = 'homepage-banners/old-banner.jpg';
    Storage::disk('public')->put($oldPath, 'old banner');
    Setting::updateOrCreate(
        ['key' => 'homepage_banner'],
        [
            'value' => $oldPath,
            'type' => 'image',
            'group' => 'homepage',
            'description' => 'Banner utama yang ditampilkan di halaman beranda',
        ]
    );
    expect(Setting::get('homepage_banner'))->toBe($oldPath);

    $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
        'settings' => [],
        'homepage_banner' => UploadedFile::fake()->image('new-banner.jpg', 1600, 900),
    ]);

    $response->assertRedirect(route('admin.settings.index'));
    $response->assertSessionHas('success');

    $newPath = Setting::query()->where('key', 'homepage_banner')->value('value');
    expect($newPath)->toStartWith('homepage-banners/')->not->toBe($oldPath);
    expect(Setting::get('homepage_banner'))->toBe($newPath);
    Storage::disk('public')->assertExists($newPath);
    Storage::disk('public')->assertMissing($oldPath);

    $this->get('/')
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($newPath), false);
});

test('invalid homepage banner uploads retain the existing banner', function () {
    Storage::fake('public');
    $existingPath = 'homepage-banners/existing-banner.jpg';
    Storage::disk('public')->put($existingPath, 'existing banner');
    Setting::updateOrCreate(
        ['key' => 'homepage_banner'],
        [
            'value' => $existingPath,
            'type' => 'image',
            'group' => 'homepage',
            'description' => 'Banner utama yang ditampilkan di halaman beranda',
        ]
    );

    $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
        'settings' => [],
        'homepage_banner' => UploadedFile::fake()->create('banner.pdf', 100, 'application/pdf'),
    ]);

    $response->assertSessionHasErrors('homepage_banner');
    $this->assertDatabaseHas('settings', ['key' => 'homepage_banner', 'value' => $existingPath]);
    Storage::disk('public')->assertExists($existingPath);
});

test('homepage banner uploads larger than five megabytes are rejected', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
        'settings' => [],
        'homepage_banner' => UploadedFile::fake()->image('oversized-banner.jpg')->size(5121),
    ]);

    $response->assertSessionHasErrors('homepage_banner');
});

test('homepage banner upload and reset cannot be requested together', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
        'settings' => [],
        'homepage_banner' => UploadedFile::fake()->image('new-banner.jpg'),
        'remove_homepage_banner' => true,
    ]);

    $response->assertSessionHasErrors(['homepage_banner', 'remove_homepage_banner']);
});

test('admin can reset the homepage banner without deleting unrelated public files', function () {
    Storage::fake('public');
    $managedPath = 'homepage-banners/custom-banner.jpg';
    $unrelatedPath = 'products/unrelated.jpg';
    Storage::disk('public')->put($managedPath, 'custom banner');
    Storage::disk('public')->put($unrelatedPath, 'unrelated image');
    Setting::updateOrCreate(
        ['key' => 'homepage_banner'],
        [
            'value' => $managedPath,
            'type' => 'image',
            'group' => 'homepage',
            'description' => 'Banner utama yang ditampilkan di halaman beranda',
        ]
    );

    $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
        'settings' => [],
        'remove_homepage_banner' => true,
    ]);

    $response->assertRedirect(route('admin.settings.index'));
    $this->assertDatabaseHas('settings', ['key' => 'homepage_banner', 'value' => '']);
    Storage::disk('public')->assertMissing($managedPath);
    Storage::disk('public')->assertExists($unrelatedPath);

    $this->get('/')
        ->assertOk()
        ->assertSee(asset('images/banner.webp'), false);
});

test('reset does not delete a banner path outside the managed directory', function () {
    Storage::fake('public');
    $unmanagedPath = 'products/legacy-banner.jpg';
    Storage::disk('public')->put($unmanagedPath, 'legacy banner');
    Setting::updateOrCreate(
        ['key' => 'homepage_banner'],
        [
            'value' => $unmanagedPath,
            'type' => 'image',
            'group' => 'homepage',
            'description' => 'Banner utama yang ditampilkan di halaman beranda',
        ]
    );

    $this->actingAs($this->admin)->put(route('admin.settings.update'), [
        'settings' => [],
        'remove_homepage_banner' => true,
    ])->assertRedirect(route('admin.settings.index'));

    Storage::disk('public')->assertExists($unmanagedPath);
});
