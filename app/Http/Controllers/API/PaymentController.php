<?php

namespace App\Http\Controllers\API;

use App\Enums\BillingType;
use App\Events\PaymentCreated;
use App\Factories\PaymentGatewayFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Traits\ApiResponse;
use App\Models\Payment;
use Carbon\Carbon;
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

    public function show(Payment $payment)
    {
        Gate::authorize('view', $payment);

        return new PaymentResource($payment);
    }

    public function store(PaymentRequest $request)
    {
        try {
            DB::beginTransaction();

            $client = $request->user()->client;

            $data   = $request->validated();

            $payment = $client->payments()->create($data);

            PaymentCreated::dispatch($payment, $data);

            DB::commit();

            return $this->responseWithSuccess(
                data: ['id' => $payment->id],
                successMessage: 'Payment was created successfully',
                successCode: Response::HTTP_CREATED
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
            // return $this->responseWithError($e);
        }

        /*
            [customer] Identificador único do cliente no Asaas
            string
            required

            [billingType] Forma de pagamento
            string
            required
            Default: UNDEFINED

            [value] Valor da cobrança
            float
            required

            [dueDate] Data de vencimento da cobrança
            date
            required
         */
    }

    public function delete(Payment $payment)
    {}
}
