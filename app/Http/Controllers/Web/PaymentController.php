<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(): View
    {
        return view('payments.index');
    }

    public function create(): view
    {
        return view('payments.create');
    }

    public function show(): View
    {
        return view('payments.show');
    }

    public function error(): View
    {
        return view('payments.error');
    }
}
