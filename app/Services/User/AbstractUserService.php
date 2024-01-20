<?php

namespace App\Services\User;

use App\Repositories\Interfaces\UsersRepositoryInterface;

abstract class AbstractUserService
{
	use Partials\PasswordHashTrait;

	public function __construct(
		protected UsersRepositoryInterface $repository
	) {
	}
}