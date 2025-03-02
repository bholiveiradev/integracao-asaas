<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
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
            'cpf_cnpj' => preg_replace("/[^0-9]/", '', $this->cpf_cnpj),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                  => 'required|string',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'cpf_cnpj'              => 'required|string|unique:customers,cpf_cnpj|cpf_ou_cnpj',
            'phone'                 => 'nullable|string|telefone_com_ddd',
            'mobile_phone'          => 'nullable|string|celular_com_ddd',
        ];
    }

    public function messages()
    {
        return [
            'cpf_cnpj.cpf_ou_cnpj' => 'The cpf cnpj field is not a valid CPF or CNPJ.',
            'phone.telefone_com_ddd' => 'The phone field is not a phone with a valid area code.',
            'mobile_phone.celular_com_ddd' => 'The mobile phone field is not a cell phone with a valid area code.',
        ];
    }
}
