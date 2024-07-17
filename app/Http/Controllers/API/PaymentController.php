<?php

namespace App\Http\Controllers\API;

use App\Events\PaymentCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Traits\ApiResponse;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PaymentController extends Controller
{
    use ApiResponse;

    /**
     * Return the list of payments
     *
     * @param Request $request
     *
     * @return JsonResource
     */
    public function index(Request $request): JsonResource
    {
        $client = $request->user()->client;

        $payments = $client->payments()->orderBy('created_at', 'desc')->paginate();

        return PaymentResource::collection($payments);
    }

    /**
     * The the payment
     *
     * @param Payment $payment
     *
     * @return JsonResource
     */
    public function show(Payment $payment): JsonResource
    {
        Gate::authorize('view', $payment);

        return new PaymentResource($payment);
    }

    /**
     * Store a new payment
     *
     * @param PaymentRequest $request
     *
     * @return JsonResponse
     */
    public function store(PaymentRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data       = $request->validated();
            $client     = $request->user()->client;
            $payment    = $client->payments()->create($data);

            PaymentCreated::dispatch($payment, $data);

            DB::commit();

            return $this->responseWithSuccess(
                data: ['id' => $payment->id],
                successMessage: 'Payment was created successfully',
                successCode: Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->responseWithError($e);
        }
    }
}
