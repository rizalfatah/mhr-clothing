<?php

use App\Http\Controllers\Moment\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/exclusive', [PageController::class, 'package'])->defaults('package', 'exclusive')->name('exclusive');
Route::get('/portrait', [PageController::class, 'package'])->defaults('package', 'portrait')->name('portrait');
Route::get('/group-portrait', [PageController::class, 'package'])->defaults('package', 'group-portrait')->name('group-portrait');
Route::get('/preset-desain', [PageController::class, 'presets'])->name('presets');

Route::get('/{package}', [PageController::class, 'package'])->name('packages.show');

// Keep every unrecognised Moment URL out of the store routes, including auth.
Route::any('/{path}', [PageController::class, 'notFound'])->where('path', '.*')->name('not-found');
