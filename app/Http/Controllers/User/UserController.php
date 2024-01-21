<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractController;
use App\Services\User\UpdateUserPasswordService;
use App\Services\User\GetUserService;
use App\Services\User\GetUserByIdService;
use App\Services\User\UpdateUserEmailService;
use App\Services\User\VerifyUserPasswordService;
use App\Services\User\DeleteUserService;
use App\Services\User\RestoreUserService;
use App\Services\User\UpdateUserDataService;
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
		return $service->handle((int) $userId);
	}

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        UpdateUserPasswordService $passwordService,
        UpdateUserEmailService $emailService,
        VerifyUserPasswordService $verifyPasswordService,
        GetUserByIdService $getUserByIdService,
        UpdateUserDataService $dataService,
    ) {
        $request->validate([
            'password_confirmation' => 'required_with:password,email',
			'password' => [Rules\Password::defaults()],
            'email' => ['email', 'unique:users'],
            'name' => ['string', 'min:3', 'max:255'],
        ]);

        $userId = $request->route('userId') ?? $request->user()->id;

        $isUpdatingSensitiveData = $request->has('password') || $request->has('email');

        if ($isUpdatingSensitiveData) {
            $verifyPasswordService->handle(
                userId: $request->user()->id,
                password: $request['password_confirmation'],
            );
        }

        if ($request->has('password')) {
            $passwordService->handle(
                userId: $userId,
                password: $request->password,
            );
        }

        if ($request->has('email')) {
            $emailService->handle(
                userId: $userId,
                email: $request->email,
            );
        }

        if ($request->has('name')) {
            $dataService->handle(
                userId: $userId,
                name: $request->name,
            );
        }

        return $getUserByIdService->handle($userId);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, DeleteUserService $service)
    {
        if ($request->user()->id === (int) $request->route('userId')) {
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
