<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class UserPasswordChanged
{
    use SerializesModels;

    public function __construct(
        public User $user
    ) {
        //
    }
}
