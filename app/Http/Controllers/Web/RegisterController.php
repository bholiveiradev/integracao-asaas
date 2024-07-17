<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function __invoke()
    {
        return view('auth.register');
    }
}
