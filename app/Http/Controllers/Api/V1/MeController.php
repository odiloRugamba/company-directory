<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\IncubatorProfile;
use App\Models\StartupProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MeController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load('incubatorProfile', 'startupProfile');

        return response()->json([
            'data' => new UserResource($user),
            'meta' => null,
            'message' => null,
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role === Role::Incubator) {
            $profile = $user->incubatorProfile;
            $this->authorize('update', $profile);
            $data = $request->validated();
            if (isset($data['organization_name'])) {
                $data['slug'] = Str::slug($data['organization_name']);
            }
            $profile->update($data);
        } else {
            $profile = $user->startupProfile;
            $this->authorize('update', $profile);
            $data = $request->validated();
            if (isset($data['company_name'])) {
                $data['slug'] = Str::slug($data['company_name']);
            }
            $profile->update($data);
        }

        return response()->json([
            'data' => new UserResource($user->fresh(['incubatorProfile', 'startupProfile'])),
            'meta' => null,
            'message' => 'Profile updated successfully.',
        ]);
    }
}
