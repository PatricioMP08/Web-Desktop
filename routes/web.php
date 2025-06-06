<?php

use Illuminate\Support\Facades\Route;

// routes/web.php
Route::get('/', function () {
    return redirect('/desktop');
});

Route::get('/login', fn() => view('login'));
Route::get('/desktop', fn() => view('desktop'));
Route::get('/admin', fn() => view('admin'));
