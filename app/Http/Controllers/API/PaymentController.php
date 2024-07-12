<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentController extends Controller
{
    public function __construct()
    {}

    /**
     * Return the list of payments
     *
     * @param Request $request
     *
     * @return JsonResource
     */
    public function index(Request $request): JsonResource
    {
        $user = $request->user();

        $payments = $user->client->payments;

        return PaymentResource::collection($payments);
    }

    public function show(Payment $payment)
    {}

    public function store()
    {}

    public function delete(Payment $payment)
    {}
}
