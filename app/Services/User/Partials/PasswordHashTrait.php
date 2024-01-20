<?php

namespace App\Services\User\Partials;

use Illuminate\Support\Facades\Hash;

trait PasswordHashTrait
{
	private function hashPassword(string $password): string
	{
		return Hash::make($password);
	}
}