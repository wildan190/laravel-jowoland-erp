<?php

namespace App\Http\Controllers\Auth;

use App\Action\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request, RegisterAction $action)
    {
        $user = $action->execute($request->validated());
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
