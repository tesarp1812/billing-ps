<?php

namespace App\Exceptions;

use App\Support\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if (! $this->isApiRequest($request)) {
            return parent::render($request, $e);
        }

        if ($e instanceof ValidationException) {
            return ApiResponse::error('Validasi gagal.', $e->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof AuthenticationException) {
            return ApiResponse::error('Unauthenticated.', null, Response::HTTP_UNAUTHORIZED);
        }

        if ($e instanceof AuthorizationException) {
            return ApiResponse::error('Anda tidak memiliki akses untuk aksi ini.', null, Response::HTTP_FORBIDDEN);
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return ApiResponse::error('Resource tidak ditemukan.', null, Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponse::error('HTTP method tidak diizinkan untuk endpoint ini.', null, Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($e instanceof TooManyRequestsHttpException) {
            return ApiResponse::error('Terlalu banyak request. Coba lagi nanti.', null, Response::HTTP_TOO_MANY_REQUESTS);
        }

        $status = $e instanceof HttpExceptionInterface
            ? $e->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($status >= Response::HTTP_INTERNAL_SERVER_ERROR) {
            Log::error('API exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);
        }

        return ApiResponse::error(
            $this->apiExceptionMessage($e, $status),
            config('app.debug') ? [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : null,
            $status
        );
    }

    protected function isApiRequest(Request $request): bool
    {
        return $request->is('api/*') || $request->expectsJson();
    }

    protected function apiExceptionMessage(Throwable $e, int $status): string
    {
        if ($status >= Response::HTTP_INTERNAL_SERVER_ERROR) {
            return config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada server.';
        }

        return $e->getMessage() ?: (Response::$statusTexts[$status] ?? 'Request gagal.');
    }
}
