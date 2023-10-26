<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PDOException;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        PDOException::class => LogLevel::CRITICAL,
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->renderable($this->buildMessage());
    }

    /**
     * Método responsável por construir a mensagem de erro.
     *
     * @return callable
     */
    private function buildMessage(): callable
    {
        return function (Throwable $e): Response {
            if (is_int($e->getCode()) && $e->getCode() >= 100 && $e->getCode() < 600) {
                return $this->sendResponse($e->getMessage(), $e->getCode());
            }

            if ($e instanceof ValidationException) {
                return $this->sendResponse([
                    "message" => $e->getMessage(),
                    "errors"  => $e->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($e instanceof NotFoundHttpException) {
                return $this->sendResponse("Rota não encontrada.", Response::HTTP_NOT_FOUND);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return $this->sendResponse("Método HTTP não permitido.", Response::HTTP_METHOD_NOT_ALLOWED);
            }

            if ($e instanceof ThrottleRequestsException) {
                return $this->sendResponse("Muitas requisições sucessivas, aguarde um momento.",
                    Response::HTTP_TOO_MANY_REQUESTS
                );
            }

            Log::error("Erro ao processar requisição", ["exception" => $e]);

            $identificator = Log::getLogger()->getProcessors()[0]->getUid();

            return $this->sendResponse("Ocorreu um erro interno do servidor. Caso o erro persista, contate o suporte ({$identificator}).",
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        };
    }

    /**
     * Método responsável por retornar a resposta para o cliente.
     *
     * @param mixed $content
     * @param int $code
     *
     * @return JsonResponse
     */
    protected function sendResponse(mixed $content, int $code): JsonResponse
    {
        if (is_string($content)) {
            $content = ["message" => $content];
        }

        return response()->json($content)
            ->setStatusCode($code);
    }
}
