<?php

test('community gallery is rendered from route-supplied masonry image data', function () {
    $response = $this->get('/community');

    $response
        ->assertOk()
        ->assertViewIs('community')
        ->assertViewHas('communityImages');

    $communityImages = $response->viewData('communityImages');

    expect($communityImages)->toHaveCount(4)
        ->and($communityImages)->each->toHaveKeys(['src', 'alt', 'caption', 'width', 'height']);

    $response
        ->assertSeeInOrder([
            'images/community/1.png',
            'images/community/2.png',
            'images/community/3.png',
            'images/community/4.png',
        ], false)
        ->assertSeeInOrder([
            'Style Inspiration',
            'Fashion Forward',
            'Street Style',
            'Urban Vibes',
        ])
        ->assertSee('data-masonry-grid', false)
        ->assertSee('data-masonry-item', false)
        ->assertSee('data-masonry-content', false)
        ->assertSee('[grid-auto-flow:dense]', false)
        ->assertSee('width="294"', false)
        ->assertSee('height="392"', false)
        ->assertSee('loading="eager"', false)
        ->assertSee('loading="lazy"', false)
        ->assertDontSee('row-span-', false);

    expect(file_get_contents(resource_path('views/community.blade.php')))
        ->not->toContain('row-span-')
        ->not->toContain('object-cover');
});

test('community gallery supports square image dimensions and omits an absent caption overlay', function () {
    $html = view('community', [
        'communityImages' => [[
            'src' => 'images/community/square-example.png',
            'alt' => 'Community member wearing a jacket',
            'width' => 400,
            'height' => 400,
        ]],
    ])->render();

    expect($html)
        ->toContain('alt="Community member wearing a jacket"')
        ->toContain('width="400"')
        ->toContain('height="400"')
        ->not->toContain('data-masonry-caption');
});
