<?php

namespace App\Http\Requests;

use App\Enums\BillingType;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LVR\CreditCard\{CardCvc, CardExpirationDate, CardNumber};

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'card_number'   => preg_replace("/[^0-9]/", '', $this->card_number),
            'postal_code'   => preg_replace("/[^0-9-]/", '', $this->postal_code),
            'due_date'      => $this->billing_type === 'BOLETO' ? Carbon::now()->addDays(5)->format('Y-m-d') : null,
        ]);

        if ($this->billing_type === 'CREDIT_CARD') {
            $this->merge([
                'card_expiration_month' => Carbon::createFromFormat('m/y', $this->card_expiration)->format('m'),
                'card_expiration_year'  => Carbon::createFromFormat('m/y', $this->card_expiration)->format('Y'),
                'cpf_cnpj'              => preg_replace("/[^0-9]/", '',$this->cpf_cnpj),
                'postal_code'           => preg_replace("/[^0-9-]/", '',$this->postal_code),
                'phone'                 => preg_replace("/[^0-9]/", '',$this->phone),
                'mobile_phone'          => preg_replace("/[^0-9]/", '',$this->mobile_phone),
                'remote_ip'             => $this->ip(),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'description'       => 'nullable|string|max:255',
            'amount'            => 'required|decimal:0,4|min:0.0001|max:999999999999.9999',
            'billing_type'      => ['required','string',Rule::enum(BillingType::class)],
            'installment_count' => 'nullable|integer|max:12',
            'due_date'          => 'nullable',
        ];

        if ($this->input('billing_type') === 'CREDIT_CARD') {
            $rules = array_merge($rules, [
                'card_number'           => ['required', new CardNumber],
                'card_holder_name'      => 'required|string|max:255',
                'card_expiration'       => 'required|after:today',
                'card_expiration_month' => 'required',
                'card_expiration_year'  => 'required',
                'cvv'                   => ['required', new CardCvc($this->input('card_number')),],
                'installment_count'     => 'required|integer|min:1|max:12',
                'email'                 => 'required|string|email',
                'cpf_cnpj'              => 'required|string|unique:clients,cpf_cnpj|cpf_ou_cnpj',
                'postal_code'           => 'required|string|max:9|formato_cep',
                'address_number'        => 'required|string|max:10',
                'address_complement'    => 'nullable|string|max:255',
                'phone'                 => 'nullable|string|telefone_com_ddd',
                'mobile_phone'          => 'nullable|string|celular_com_ddd',
                'remote_ip'             => 'nullable|string'
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'card_number' => [
                'validation.credit_card.card_checksum_invalid' => 'Invalid card number',
            ],
            'cvv' => [
                'validation.credit_card.card_cvc_invalid' => 'Invalid credit card cvv',
            ],
            'cpf_cnpj.cpf_ou_cnpj' => 'The cpf cnpj field is not a valid CPF or CNPJ.',
            'card_expiration.date_format' => 'The expiry date field must match the format MM/YY.',
            'postal_code.formato_cep' => 'The postal code field does not have a valid postal code format.',
            'phone.telefone_com_ddd' => 'The phone field is not a phone with a valid area code.',
            'mobile_phone.celular_com_ddd' => 'The mobile phone field is not a cell phone with a valid area code.',
        ];
    }
}
