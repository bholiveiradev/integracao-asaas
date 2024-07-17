<?php

use App\Http\Controllers\Web\{
    LoginController,
    RegisterController
};
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('login', LoginController::class);
Route::get('register', RegisterController::class);
