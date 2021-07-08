<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'cpf_cnpj' => 'required|cpf_cnpj|unique:users,cpf_cnpj',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|alpha_num',
            'role' => 'required'
        ];

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
