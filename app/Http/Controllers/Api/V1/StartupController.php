<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\StartupProfileResource;
use App\Models\StartupProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StartupController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min((int) $request->get('per_page', 15), 50);

        $query = StartupProfile::with('user');

        if ($request->filled('country')) {
            $query->byCountry($request->country);
        }
        if ($request->filled('sector')) {
            $query->bySector($request->sector);
        }
        if ($request->filled('stage')) {
            $query->byStage($request->stage);
        }
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $startups = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => StartupProfileResource::collection($startups),
            'meta' => [
                'current_page' => $startups->currentPage(),
                'last_page' => $startups->lastPage(),
                'per_page' => $startups->perPage(),
                'total' => $startups->total(),
            ],
            'message' => null,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $profile = StartupProfile::with('user')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => new StartupProfileResource($profile),
            'meta' => null,
            'message' => null,
        ]);
    }
}
