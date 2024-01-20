<?php

namespace App\Services\User\Partials;

use Illuminate\Support\Facades\Hash;

trait PasswordHashTrait
{
	protected function hashPassword(string $password): string
	{
		return Hash::make($password);
	}

	protected function checkPassword(string $password, string $hash): bool
	{
		return Hash::check($password, $hash);
	}
}