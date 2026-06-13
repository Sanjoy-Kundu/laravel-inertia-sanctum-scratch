<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home'); // 👈 resources/js/Pages/Home.vue ফাইলটিকে রেন্ডার করবে
});
