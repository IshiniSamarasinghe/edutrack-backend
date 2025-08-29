<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    public function index(Request $request)
    {
        $q       = trim($request->query('q',''));
        $perPage = min(100, max(5, (int)$request->query('per_page', 10)));
        $sort    = $request->query('sort','created_at');
        $dir     = $request->query('dir','desc');

        $rows = Admin::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy($sort, $dir)
            ->paginate($perPage);

        return response()->json($rows);
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();
        return response()->json(['status'=>'ok']);
    }
}
