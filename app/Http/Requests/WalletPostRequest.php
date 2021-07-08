<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class WalletPostRequest extends FormRequest
{

    public function authorize()
    {
        if (auth()->user()->hasRole('consumidor')) {
            return true;
        }
        return false;
    }

    public function rules()
    {
        $params = $this->request->all();

        $rules = [
            'value' => ['required','regex:/^\d+(\.\d{1,2})?$/'],
        ];

        if ($this->route()->uri == "api/wallet/do-transfer") {
            $rules['email'] = 'email|sometimes';
            $rules['cpf_cnpj'] = 'cpf_cnpj|sometimes';
            $rules['message'] = 'max:500';
        }

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors(); // Here is your array of errors
        $response = response()->json([
            "mensagem" => "Erro no envio de dados",
            "detalhes" => $errors->messages()
        ], 422);
        throw new HttpResponseException($response);
    }
}
