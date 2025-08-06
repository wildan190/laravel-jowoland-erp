<?php

namespace App\Action\Auth;

use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public function execute(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }
}
