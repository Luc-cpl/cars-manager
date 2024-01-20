<?php

namespace App\Exceptions;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
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
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof InvalidArgumentException) {
            return $this->handleInvalidArgumentException($exception);
        }

        if ($exception instanceof UniqueConstraintViolationException) {
            return $this->handleUniqueConstraintViolationException($exception);
        }

        if ($exception instanceof InvalidPasswordException) {
            return $this->handleInvalidPasswordException($exception);
        }

        return parent::render($request, $exception);
    }

    protected function handleInvalidArgumentException(InvalidArgumentException $exception)
    {
        return new JsonResponse([
            'error' => $exception->getMessage(),
            'code' => 'invalid_argument',
        ], 400);
    }

    protected function handleUniqueConstraintViolationException(UniqueConstraintViolationException $exception)
    {
        return new JsonResponse([
            'error' => 'Unique constraint violation',
            'code' => 'unique_constraint_violation',
        ], 400);
    }

    protected function handleInvalidPasswordException(InvalidPasswordException $exception)
    {
        return new JsonResponse([
            'error' => 'Invalid password',
            'code' => 'invalid_password',
        ], 400);
    }
}
