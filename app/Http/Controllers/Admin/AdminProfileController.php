<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function show(Request $request)
    {
        $u = $request->user(); // authenticated admin
        return response()->json([
            'data' => [
                'id'    => $u->id,
                'name'  => $u->name,
                'email' => $u->email,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $u = $request->user();

        $data = $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => [
                'required','email','max:255',
                Rule::unique('users','email')->ignore($u->id),
            ],
        ]);

        $u->fill($data)->save();

        return response()->json([
            'message' => 'Profile updated',
            'data'    => [
                'id'    => $u->id,
                'name'  => $u->name,
                'email' => $u->email,
            ],
        ]);
    }
}
