<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer a requisição.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retorna os dados para validação.
     *
     * @return array<string, mixed>|null
     */
    public function validationData(): array|null
    {
        return $this->post();
    }

    /**
     * Retorna as mensagens de erro personalizadas para as regras de validação.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "email.email"       => "O endereço de e-mail informado é inválido.",
            "email.unique"      => "O endereço de e-mail informado já está em uso.",
            "password.string"   => "A senha deve ser uma string.",
            "password.min"      => "A senha deve conter no mínimo 8 caracteres."
        ];
    }

    /**
     * Regras de validação para a requisição de criação de usuário.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            "name"     => ["required", "string", "min:2", "max:255"],
            "email"    => ["required", "email", "max:100", "unique:users,email"],
            "password" => ["required", "string","min:8"]
        ];
    }
}
