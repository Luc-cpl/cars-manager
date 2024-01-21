<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractController;
use App\Services\User\UpdatePasswordService;
use App\Services\User\GetUserService;
use App\Services\User\GetUserByIdService;
use App\Services\User\UpdateEmailService;
use App\Services\User\VerifyPasswordService;
use App\Services\User\DeleteUserService;
use App\Services\User\RestoreUserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use InvalidArgumentException;

class UserController extends AbstractController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GetUserService $service)
    {
        return $service->handle($request->query());
    }

    /**
     * Display the requested or current user.
     */
    public function show(Request $request, GetUserByIdService $service)
	{
        $userId = $request->route('userId') ?? $request->user()->id;
		return $service->handle($userId);
	}

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        UpdatePasswordService $passwordService,
        UpdateEmailService $emailService,
        VerifyPasswordService $verifyPasswordService,
        GetUserByIdService $getUserByIdService,
    ) {
        $request->validate([
			'password' => ['string', 'min:6'],
            'new_password' => [Rules\Password::defaults()],
            'email' => ['email', 'unique:users'],
        ]);

        $isCurrentUser = $request->user()->id === $request->route('userId');
        $userId = $request->route('userId') ?? $request->user()->id;

        if ($request->has('password') && $isCurrentUser) {
            $verifyPasswordService->handle(
                userId: $userId,
                password: $request->password,
            );
        } elseif (!$request->has('password') && $isCurrentUser) {
            throw new InvalidArgumentException('Password is required');
        }

        $setNewPassword = $isCurrentUser && $request->has('new_password');
        $setNewPassword = $setNewPassword || !$isCurrentUser && $request->has('password');

        if ($setNewPassword) {
            $password = $request->has('new_password')
                ? $request->new_password
                : $request->password;

            $passwordService->handle(
                userId: $userId,
                password: $password,
            );
        }

        if ($request->has('email')) {
            $emailService->handle(
                userId: $userId,
                email: $request->email,
            );
        }

        return $getUserByIdService->handle($userId);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, DeleteUserService $service)
    {
        if ($request->user()->id === $request->route('userId')) {
            throw new InvalidArgumentException('You cannot delete yourself');
        }

        return $service->handle(
            userId: $request->route('userId')
        );
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, RestoreUserService $service)
    {
        return $service->handle(
            userId: $request->route('userId')
        );
    }
}
