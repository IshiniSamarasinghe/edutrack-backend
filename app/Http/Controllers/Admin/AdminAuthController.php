<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

// ✅ Laravel 11 controller middleware
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminAuthController extends Controller implements HasMiddleware
{
    /**
     * In Laravel 11, declare controller middleware statically.
     * Only protect `me` and `logout` with the admin guard.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:admin', only: ['me', 'logout']),
        ];
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:admins,email'],
            'password' => ['required', Password::min(6)],
        ]);

        // password hashed by Admin model mutator
        $admin = Admin::create($data);

        return response()->json(['status' => 'ok', 'admin' => $admin], 201);
    }

   public function login(Request $request)
{
    $data = $request->validate([
        'email' => ['required','email'],
        'password' => ['required'],
        'remember' => ['boolean'],
    ]);

    if (! \Auth::guard('admin')->attempt(
        ['email' => $data['email'], 'password' => $data['password']],
        $request->boolean('remember')
    )) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $request->session()->regenerate(); // CRITICAL
    return response()->json(['ok' => true], 200);
}

    public function me(Request $request)
    {
        return response()->json(['admin' => Auth::guard('admin')->user()]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['status' => 'ok']);
    }
}
