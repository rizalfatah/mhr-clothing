<?php

use App\Http\Controllers\Moment\PageController;
use App\Models\MomentPackage;
use App\Services\MomentContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    config(['session.driver' => 'array', 'cache.default' => 'array']);
});

test('all five Moment pages render on their own domain', function (string $path, string $view, string $heading) {
    $this->get('https://'.config('moment.domain').$path)
        ->assertOk()
        ->assertViewIs($view)
        ->assertSee($heading)
        ->assertDontSee('Cart Sidebar');
})->with([
    ['/', 'moment.home', 'Every Graduation Has a'],
    ['/exclusive', 'moment.package', 'Exclusive'],
    ['/portrait', 'moment.package', 'Portrait'],
    ['/group-portrait', 'moment.package', 'Group Portrait'],
    ['/preset-desain', 'moment.presets', 'Preset & Desain'],
]);

test('the two homepages resolve independently', function () {
    $routes = Route::getRoutes();
    $moment = $routes->match(Request::create('https://'.config('moment.domain').'/'));
    $store = $routes->match(Request::create('https://mahrmofficial.com/'));

    expect($moment->getName())->toBe('moment.home')
        ->and($moment->getActionName())->toBe(PageController::class.'@home')
        ->and($store->getName())->toBe('home');
});

test('budget steps stay rounded and cover active package prices after edits', function (array $prices, int $minimum, int $maximum, int $initial) {
    $packages = MomentPackage::orderBy('id')->get();
    foreach ($packages as $index => $package) {
        $package->update([
            'is_active' => isset($prices[$index]),
            'data' => [...$package->data, 'price' => $prices[$index] ?? $package->data['price']],
        ]);
    }

    $this->get('https://'.config('moment.domain').'/')
        ->assertOk()
        ->assertViewHas('budget_step', 50000)
        ->assertViewHas('budget_min', $minimum)
        ->assertViewHas('budget_max', $maximum)
        ->assertViewHas('budget_initial', $initial);

    if ($prices) {
        expect($minimum)->toBeLessThanOrEqual(min($prices))
            ->and($maximum)->toBeGreaterThanOrEqual(max($prices))
            ->and($initial)->toBeGreaterThanOrEqual(min($prices));
    }
})->with([
    'default prices' => [[250000, 300000, 450000], 250000, 450000, 300000],
    'prices between steps' => [[275000, 325000, 479000], 250000, 500000, 300000],
    'single package above initial budget' => [[410000], 400000, 450000, 450000],
    'equal prices' => [[100000, 100000], 100000, 150000, 150000],
    'no active packages' => [[], 0, 50000, 50000],
]);

test('Moment package URLs are unavailable on the MAHRM domain', function () {
    $this->get('https://mahrmofficial.com/exclusive')->assertNotFound();
});

test('store routes cannot be accessed through Moment', function (string $path) {
    $this->get('https://'.config('moment.domain').$path)->assertNotFound()->assertViewIs('moment.not-found');
})->with(['/catalog', '/login', '/admin/dashboard', '/cart']);

test('package pages show their own benefits and booking message', function () {
    $this->get('https://'.config('moment.domain').'/exclusive')
        ->assertSee('30 Edited Photos')
        ->assertSee('Dedicated Assistant')
        ->assertSee('14-Day File Storage')
        ->assertSee(rawurlencode('Moment Exclusive'), false)
        ->assertSee('Estimasi awal untuk preview');

    $this->get('https://'.config('moment.domain').'/portrait')
        ->assertSee('25 Edited Photos')
        ->assertSee('7-Day File Storage')
        ->assertDontSee('Dedicated Assistant');
});

test('all configured image slots use nonempty local assets', function () {
    $assets = [config('moment.hero'), config('moment.brand_logo'), config('moment.collaboration_logo')];
    foreach (config('moment.packages') as $package) {
        $assets[] = $package['image'];
        array_push($assets, ...$package['gallery']);
    }
    foreach (config('moment.presets') as $preset) {
        $assets[] = $preset['image'];
    }
    foreach (array_unique($assets) as $asset) {
        expect(is_file(public_path($asset)))->toBeTrue()
            ->and(filesize(public_path($asset)))->toBeGreaterThan(0);
    }
});
