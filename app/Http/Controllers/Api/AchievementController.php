<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\AchievementMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AchievementController extends Controller
{
    // GET /api/achievements (mine)
    public function index(Request $request)
    {
        $items = Achievement::query()
            ->where('user_id', $request->user()->id)
            ->with('media')            // returns media[].url
            ->latest()
            ->get(['id','title','description as desc','link','date','created_at']);

        return response()->json($items);
    }

    // POST /api/achievements
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'desc'  => ['nullable','string','max:2000'],
            'link'  => ['nullable','string','max:1024'],
            'date'  => ['nullable','date'],
            'files' => ['nullable','array','max:10'],
            'files.*' => ['file','mimetypes:image/jpeg,image/png','max:5120'], // 5MB each
        ]);

        $ach = Achievement::create([
            'user_id'     => $request->user()->id,
            'title'       => $data['title'],
            'description' => $data['desc'] ?? null,
            'link'        => $data['link'] ?? null,
            'date'        => $data['date'] ?? null,
        ]);

        // Save images to storage/app/public/achievements/{userId}/...
        if (!empty($data['files'])) {
            foreach ($data['files'] as $uploaded) {
                $path = $uploaded->store("achievements/{$request->user()->id}", 'public');
                AchievementMedia::create([
                    'achievement_id' => $ach->id,
                    'path' => $path,
                    'mime' => $uploaded->getClientMimeType(),
                    'size' => $uploaded->getSize(),
                ]);
            }
        }

        return response()->json(
            $ach->load('media')->only(['id','title']) + ['message' => 'Saved'],
            201
        );
    }

    // DELETE /api/achievements/{id}
    public function destroy(Request $request, Achievement $achievement)
    {
        abort_unless($achievement->user_id === $request->user()->id, 403);

        // remove files
        foreach ($achievement->media as $m) {
            Storage::disk('public')->delete($m->path);
        }
        $achievement->delete();

        return response()->noContent();
    }
}
