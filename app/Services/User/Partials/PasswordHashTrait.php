<?php

namespace App\Services\User\Partials;

use Illuminate\Support\Facades\Hash;

trait PasswordHashTrait
{
	private function hashPassword(string $password): string
	{
		return Hash::make($password);
	}

	private function checkPassword(string $password, string $hash): bool
	{
		return Hash::check($password, $hash);
	}
}