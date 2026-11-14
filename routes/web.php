<?php

use Illuminate\Support\Facades\Route;

// Register Moment first: its homepage shares the same URI as the store.
Route::domain(config('moment.domain'))
    ->name('moment.')
    ->group(base_path('routes/moment.php'));

// A nullable store domain preserves existing localhost development URLs.
Route::domain(config('moment.store_domain'))
    ->group(base_path('routes/mahrm.php'));
