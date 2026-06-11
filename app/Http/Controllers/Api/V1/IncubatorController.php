<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\IncubatorProfileResource;
use App\Models\IncubatorProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class IncubatorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['country', 'sector', 'search', 'verified']);
        $perPage = min((int) $request->get('per_page', 15), 50);
        $cacheKey = 'directory:incubators:' . md5(serialize($filters) . $perPage . $request->get('page', 1));

        $result = Cache::remember($cacheKey, 300, function () use ($filters, $perPage) {
            $query = IncubatorProfile::with('user')->withCount('user as applications_count');

            if (!empty($filters['country'])) {
                $query->byCountry($filters['country']);
            }
            if (!empty($filters['sector'])) {
                $query->bySector($filters['sector']);
            }
            if (!empty($filters['search'])) {
                $query->search($filters['search']);
            }
            if (isset($filters['verified']) && $filters['verified']) {
                $query->verified();
            }

            return $query->latest()->paginate($perPage);
        });

        return response()->json([
            'data' => IncubatorProfileResource::collection($result),
            'meta' => [
                'current_page' => $result->currentPage(),
                'last_page' => $result->lastPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
            ],
            'message' => null,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $profile = IncubatorProfile::with('user')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => new IncubatorProfileResource($profile),
            'meta' => null,
            'message' => null,
        ]);
    }
}
