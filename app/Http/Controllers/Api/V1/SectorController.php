<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SectorResource;
use App\Models\Sector;
use Illuminate\Http\JsonResponse;

class SectorController extends Controller
{
    public function index(): JsonResponse
    {
        $sectors = Sector::orderBy('name')->get();

        return response()->json([
            'data' => SectorResource::collection($sectors),
            'meta' => null,
            'message' => null,
        ]);
    }
}
