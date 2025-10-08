<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/community', function () {
    return view('community');
});

Route::get('/catalog', function () {
    return view('catalog');
});
