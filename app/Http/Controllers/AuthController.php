<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\Auth\LoginRequest;
use DomainException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Autentica um usuário.
     *
     * @param LoginRequest $request
     * @return array
     * @throws \App\Exceptions\AuthException
     */
    public function login(LoginRequest $request): array
    {
        $data = $request->validated();

        $token = Auth::attempt($data);

        if (!$token) throw new AuthException("E-mail e/ou senha inválido(s).", 400);

        return [
            "type"         => "Bearer",
            "expires_in"   => auth()->factory()->getTTl() * 60,
            "access_token" => $token
        ];
    }

    /**
     * Retorna o usuário autenticado.
     *
     * @return Authenticatable|null
     */
    public function me(): ?Authenticatable
    {
        return Auth::user();
    }
}
