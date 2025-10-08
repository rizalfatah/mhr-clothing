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

Route::get('/products/{id}', function ($id) {
    return view('product-detail', ['id' => $id]);
});

Route::get('/about', function () {
    return view('about');
});
