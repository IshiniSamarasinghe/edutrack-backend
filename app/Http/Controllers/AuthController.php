<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Accept either "index_number" (DB-friendly) or "student_number" (from UI)
        $index = $request->input('index_number') ?? $request->input('student_number');

        // Base validation
        $data = $request->validate(
            [
                'name'     => ['required','string','max:255'],
                'email'    => [
                    'required','email','max:255','unique:users,email',
                    'regex:/^[^@\s]+@stu\.kln\.ac\.lk$/i',
                ],
                'password' => ['required','string','min:6'],
                'pathway'  => ['required','string','max:100'],
            ],
            [
                'email.regex' => 'Please use your university email (@stu.kln.ac.lk).',
            ]
        );

        // Validate index number format and uniqueness (return under both possible keys)
        if (!$index || !preg_match('/^(CT|ET|CS)\d{7}$/i', $index)) {
            $msg = ['Format: CT/ET/CS followed by 7 digits (e.g., CT2019001).'];
            throw ValidationException::withMessages([
                'index_number'   => $msg,
                'student_number' => $msg,
            ]);
        }

        $indexUpper = strtoupper($index);
        if (User::where('index_number', $indexUpper)->exists()) {
            $msg = ['The index number has already been taken.'];
            throw ValidationException::withMessages([
                'index_number'   => $msg,
                'student_number' => $msg,
            ]);
        }

        // Derive type from prefix
        preg_match('/^(CT|ET|CS)/i', $index, $m);
        $type = strtoupper($m[1]);

        // Normalize pathway for DB consistency (e.g., "Software Systems" -> "software_systems")
        $pathway = Str::of($request->input('pathway'))->trim()->lower()->replace(' ', '_');

        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'index_number' => $indexUpper,
            'type'         => $type,
            'pathway'      => (string) $pathway,
        ]);

        return response()->json([
            'user' => $user->only(['id','name','email','index_number','type','pathway']),
            'message' => 'Registered successfully.',
        ], 201);
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
