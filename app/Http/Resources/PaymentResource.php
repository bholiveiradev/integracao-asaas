<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PaymentResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'gateway_name'      => $this->gateway_name,
            'description'       => $this->description,
            'reference'         => $this->reference,
            'amount'            => number_format($this->amount, 2, '.', ''),
            'billing_type'      => $this->billing_type,
            'status'            => $this->status,
            'installment_count' => $this->installment_count,
            'due_date'          => Carbon::parse($this->due_date)->format('d/m/Y'),
            'paid_at'           => Carbon::parse($this->paid_at)->format('d/m/Y'),
            'external_url'      => $this->external_url,
            'processing'        => (bool) $this->processing,
            'customer'          => CustomerResource::make($this->customer),
        ];
    }
}
