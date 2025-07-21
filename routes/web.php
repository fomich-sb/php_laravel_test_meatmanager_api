<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('/login', function () {
    return view('demo');
});
Route::get('/', function () {
    return view('demo');
});
