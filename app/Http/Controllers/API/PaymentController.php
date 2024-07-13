<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

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

        $payments = $user->client->payments()->paginate();

        return PaymentResource::collection($payments);
    }

    public function show(Payment $payment)
    {
        Gate::authorize('view', $payment);

        return new PaymentResource($payment);
    }

    public function store(Request $request)
    {

    }

    public function delete(Payment $payment)
    {}
}
