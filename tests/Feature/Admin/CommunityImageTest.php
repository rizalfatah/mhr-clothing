<?php

use App\Models\CommunityImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

test('admin can access community image management', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.community-images.index'));

    $response
        ->assertOk()
        ->assertViewIs('admin.community-images.index')
        ->assertViewHas('communityImages')
        ->assertSee('Tambah Gambar Baru')
        ->assertSee('Simpan Semua Urutan');
});

test('customer cannot access community image management', function () {
    $customer = User::factory()->create();

    $this->actingAs($customer)
        ->get(route('admin.community-images.index'))
        ->assertForbidden();
});

test('admin can upload an image to the community showcase', function () {
    Storage::fake('public');

    $response = $this->actingAs($this->admin)->post(route('admin.community-images.store'), [
        'image' => UploadedFile::fake()->image('community-look.jpg', 1200, 800),
        'alt_text' => 'Model wearing the newest community outfit',
        'caption' => 'New Community Look',
        'sort_order' => 4,
        'is_active' => true,
    ]);

    $response
        ->assertRedirect(route('admin.community-images.index'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $communityImage = CommunityImage::query()
        ->where('caption', 'New Community Look')
        ->firstOrFail();

    expect($communityImage->width)->toBe(1200)
        ->and($communityImage->height)->toBe(800)
        ->and($communityImage->is_active)->toBeTrue()
        ->and($communityImage->image_path)->toStartWith('community/');

    Storage::disk('public')->assertExists($communityImage->image_path);

    $this->get(route('community'))
        ->assertOk()
        ->assertSee(Storage::disk('public')->url($communityImage->image_path), false)
        ->assertSee('New Community Look');
});

test('community image uploads validate the file and accessible text', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.community-images.store'), [
        'image' => UploadedFile::fake()->create('not-an-image.pdf', 100, 'application/pdf'),
        'alt_text' => '',
        'is_active' => true,
    ]);

    $response->assertSessionHasErrors(['image', 'alt_text']);
});

test('admin can replace and update a community image', function () {
    Storage::fake('public');
    Storage::disk('public')->put('community/old.jpg', 'old image');

    $communityImage = CommunityImage::create([
        'image_path' => 'community/old.jpg',
        'alt_text' => 'Old alt text',
        'caption' => 'Old caption',
        'width' => 400,
        'height' => 400,
        'sort_order' => 10,
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->admin)->put(
        route('admin.community-images.update', $communityImage),
        [
            'image' => UploadedFile::fake()->image('replacement.png', 600, 900),
            'alt_text' => 'Updated accessible description',
            'caption' => 'Updated caption',
            'is_active' => false,
        ]
    );

    $response
        ->assertRedirect(route('admin.community-images.index'))
        ->assertSessionHasNoErrors();

    $communityImage->refresh();

    expect($communityImage->alt_text)->toBe('Updated accessible description')
        ->and($communityImage->caption)->toBe('Updated caption')
        ->and($communityImage->sort_order)->toBe(10)
        ->and($communityImage->width)->toBe(600)
        ->and($communityImage->height)->toBe(900)
        ->and($communityImage->is_active)->toBeFalse();

    Storage::disk('public')->assertMissing('community/old.jpg');
    Storage::disk('public')->assertExists($communityImage->image_path);

    $this->get(route('community'))
        ->assertDontSee('Updated accessible description');
});

test('admin can save every community image order at once', function () {
    $images = CommunityImage::query()->orderBy('sort_order')->get();
    $orders = $images->values()->mapWithKeys(
        fn (CommunityImage $image, int $index): array => [$image->id => $images->count() - $index - 1]
    )->all();

    $response = $this->actingAs($this->admin)->patch(
        route('admin.community-images.update-order'),
        ['orders' => $orders]
    );

    $response
        ->assertRedirect(route('admin.community-images.index'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success', 'Urutan seluruh gambar berhasil disimpan.');

    $images->each(function (CommunityImage $image) use ($orders): void {
        expect($image->refresh()->sort_order)->toBe($orders[$image->id]);
    });
});

test('bulk community image order requires every image and unique positions', function () {
    $images = CommunityImage::query()->orderBy('sort_order')->get();
    $orders = $images->mapWithKeys(
        fn (CommunityImage $image): array => [$image->id => $image->sort_order]
    )->all();
    $orders[$images[1]->id] = $orders[$images[0]->id];

    $this->actingAs($this->admin)
        ->from(route('admin.community-images.index'))
        ->patch(route('admin.community-images.update-order'), [
            'orders' => $orders,
        ])
        ->assertRedirect(route('admin.community-images.index'))
        ->assertSessionHasErrors('orders.'.$images[1]->id);

    $images->each(function (CommunityImage $image): void {
        expect($image->refresh()->sort_order)->toBe($image->getOriginal('sort_order'));
    });
});

test('admin can delete an uploaded community image and its file', function () {
    Storage::fake('public');
    Storage::disk('public')->put('community/delete-me.jpg', 'image');

    $communityImage = CommunityImage::create([
        'image_path' => 'community/delete-me.jpg',
        'alt_text' => 'Image to delete',
        'caption' => null,
        'width' => 500,
        'height' => 700,
        'sort_order' => 20,
        'is_active' => true,
    ]);

    $this->actingAs($this->admin)
        ->delete(route('admin.community-images.destroy', $communityImage))
        ->assertRedirect(route('admin.community-images.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('community_images', ['id' => $communityImage->id]);
    Storage::disk('public')->assertMissing('community/delete-me.jpg');
});
