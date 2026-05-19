<?php

use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Support\ApiResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $isApiRequest = fn (Request $request): bool => $request->is('api/*') || $request->expectsJson();

        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) use ($isApiRequest) {
            return $isApiRequest($request);
        });

        $exceptions->render(function (ValidationException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return ApiResponse::error('Validasi gagal.', $e->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return ApiResponse::error('Unauthenticated.', null, Response::HTTP_UNAUTHORIZED);
            }
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return ApiResponse::error('Anda tidak memiliki akses untuk aksi ini.', null, Response::HTTP_FORBIDDEN);
            }
        });

        $exceptions->render(function (ModelNotFoundException|NotFoundHttpException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return ApiResponse::error('Resource tidak ditemukan.', null, Response::HTTP_NOT_FOUND);
            }
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return ApiResponse::error('HTTP method tidak diizinkan untuk endpoint ini.', null, Response::HTTP_METHOD_NOT_ALLOWED);
            }
        });

        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request) use ($isApiRequest) {
            if ($isApiRequest($request)) {
                return ApiResponse::error('Terlalu banyak request. Coba lagi nanti.', null, Response::HTTP_TOO_MANY_REQUESTS);
            }
        });

        $exceptions->render(function (\Throwable $e, Request $request) use ($isApiRequest) {
            if (! $isApiRequest($request)) {
                return null;
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
                $status >= Response::HTTP_INTERNAL_SERVER_ERROR && ! config('app.debug')
                    ? 'Terjadi kesalahan pada server.'
                    : ($e->getMessage() ?: (Response::$statusTexts[$status] ?? 'Request gagal.')),
                config('app.debug') && $status >= Response::HTTP_INTERNAL_SERVER_ERROR ? [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null,
                $status
            );
        });
    })
    ->create();
