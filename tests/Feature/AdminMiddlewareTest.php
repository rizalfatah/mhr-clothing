<?php

use App\Models\User;

test('guest cannot access admin routes', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('error', 'Silakan login terlebih dahulu.');
});

test('non-admin user cannot access admin routes', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertForbidden();
});

test('admin user can access admin dashboard', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
});

test('admin user can access admin products routes', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.products.index'));

    $response->assertOk();
});

test('admin user can access admin categories routes', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.categories.index'));

    $response->assertOk();
});

test('admin user can access admin transactions routes', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.transactions.index'));

    $response->assertOk();
});

test('non-admin user cannot access admin products routes', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->get(route('admin.products.index'));

    $response->assertForbidden();
});

test('non-admin user cannot access admin categories routes', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->get(route('admin.categories.index'));

    $response->assertForbidden();
});

test('non-admin user cannot access admin transactions routes', function () {
    $user = User::factory()->create(['role' => 'customer']);

    $response = $this->actingAs($user)->get(route('admin.transactions.index'));

    $response->assertForbidden();
});
