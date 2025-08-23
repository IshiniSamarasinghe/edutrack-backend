<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Allow ONLY @stu.kln.ac.lk addresses
        $data = $request->validate(
            [
                'name'     => 'required|string|max:255',
                'email'    => [
                    'required',
                    'email',
                    'unique:users,email',
                    'regex:/^[^@\s]+@stu\.kln\.ac\.lk$/i',
                ],
                'password' => 'required|string|min:6',
            ],
            [
                'email.regex' => 'Please use your university email (@stu.kln.ac.lk).',
            ]
        );

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            // If you added an "index_no" column and made it fillable,
            // include it here (e.g., 'index_no' => $request->input('index_no'))
        ]);

        // Consistent JSON structure
        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $creds = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($creds, true)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        $request->session()->regenerate();

        return response()->json(['user' => Auth::user()]);
    }

    public function user(Request $request)
    {
        return response()->json(['user' => Auth::user()]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['ok' => true]);
    }
}
