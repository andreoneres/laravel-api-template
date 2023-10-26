<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Exception;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            return parent::handle($request, $next, ...$guards);
        } catch (Exception $e) {
            $this->unauthenticated($request, $guards);
        }
    }

    /**
     * Handle an unauthenticated user.
     *
     * @param Request $request
     * @param array $guards
     *
     * @return void
     *
     * @throws AuthException
     */
    protected function unauthenticated($request, array $guards): void
    {
        throw new AuthException("Usuário não autenticado.", 401);
    }
}
