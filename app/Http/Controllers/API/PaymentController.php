<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\Payment\PaymentContext;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentController extends Controller
{
    public function __construct(private PaymentContext $paymentContext)
    {}

    public function index(Request $request)
    {}

    public function show(Payment $payment)
    {}

    public function store()
    {}

    public function delete(Payment $payment)
    {}
}
