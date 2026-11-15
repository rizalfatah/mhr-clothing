<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Illuminate\Support\Facades\Artisan::command('moment:initialize', function () { App\Services\MomentContent::initialize(); $this->info('Konten awal Moment tersedia; perubahan admin tetap dipertahankan.'); });
