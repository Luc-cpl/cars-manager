<?php

namespace App\Exceptions;

use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
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

        $this->renderable(fn (InvalidArgumentException $exception) => response()->json([
            'error' => $exception->getMessage(),
            'code' => 'invalid_argument',
        ], 400));

        $this->renderable(fn (UniqueConstraintViolationException $exception) => response()->json([
            'error' => 'Unique constraint violation',
            'code' => 'unique_constraint_violation',
        ], 400));

        $this->renderable(fn (InvalidPasswordException $exception) => response()->json([
            'error' => 'Invalid password',
            'code' => 'invalid_password',
        ], 401));

        $this->renderable(fn (TokenBlacklistedException $exception) => response()->json([
            'error' => 'Invalid token',
            'code' => 'invalid_token',
        ], 401));
    }
}
