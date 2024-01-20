<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\User\UpdatePasswordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, UpdatePasswordService $service): JsonResponse
    {
        $request->validate([
			'old_password' => ['required', 'string', 'min:6'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $service->handle(
			userId: $request->user()->id,
            oldPassword: $request->old_password,
            password: $request->password,
        );

        return response()->json([
			'message' => 'Password updated successfully',
		], 200);
    }
}
