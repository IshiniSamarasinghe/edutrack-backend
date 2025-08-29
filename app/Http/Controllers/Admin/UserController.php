<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim($request->query('q', ''));
        $perPage = min(100, max(5, (int) $request->query('per_page', 10)));
        $sort    = $request->query('sort', 'created_at'); // id|name|email|email_verified_at|created_at|updated_at|achievements_count
        $dir     = $request->query('dir', 'desc');        // asc|desc

        $users = User::query()
            ->withCount('achievements') // NEW: exposes achievements_count
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            // Tip: sorting by achievements_count now works too
            ->orderBy($sort, $dir)
            ->paginate($perPage);

        return response()->json($users);
    }

    public function show(User $user)
    {
        // Return all non-sensitive columns (never return password hash)
        return response()->json([
            'id'                => $user->id,
            'name'              => $user->name,
            'email'             => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'remember_token'    => $user->remember_token,
            'created_at'        => $user->created_at,
            'updated_at'        => $user->updated_at,
            'achievements_count'=> $user->achievements()->count(), // NEW: include count in show()
        ]);
    }

    public function destroy(Request $request, User $user)
    {
        // prevent user from deleting themselves (optional)
        if ($request->user() && (int)$request->user()->id === (int)$user->id) {
            return response()->json(['message' => "You can't delete your own account."], 422);
        }

        $user->delete();

        return response()->json(['status' => 'ok']);
    }
}
