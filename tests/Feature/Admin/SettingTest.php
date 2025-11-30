<?php

use App\Models\Setting;
use App\Models\User;

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
