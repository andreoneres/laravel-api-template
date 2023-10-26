<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
        return $this->query();
    }

    /**
     * Retorna as mensagens de erro personalizadas para as regras de validação.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            "name.string"    => "O nome deve ser uma string.",
            "name.min"       => "O nome deve conter no mínimo 2 caracteres.",
            "name.max"       => "O nome deve conter no máximo 255 caracteres."
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
            "name" => ["string", "min:2", "max:255"],
        ];
    }
}
