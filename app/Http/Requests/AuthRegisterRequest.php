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
            'cpf_cnpj'              => 'required|string|unique:clients,cpf_cnpj|cpf_ou_cnpj',
            'phone'                 => 'nullable|string|telefone_com_ddd',
            'mobile_phone'          => 'nullable|string|celular_com_ddd',
        ];
    }
}
