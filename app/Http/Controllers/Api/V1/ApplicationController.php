<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ApplicationStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreApplicationRequest;
use App\Http\Requests\Api\UpdateApplicationStatusRequest;
use App\Http\Resources\Api\ApplicationResource;
use App\Models\ActivityLog;
use App\Models\Application;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Application::with(['startup.startupProfile', 'incubator.incubatorProfile']);

        if ($user->role === Role::Startup) {
            $query->where('startup_id', $user->id);
        } elseif ($user->role === Role::Incubator) {
            $query->where('incubator_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $applications = $query->latest()->paginate(15);

        return response()->json([
            'data' => ApplicationResource::collection($applications),
            'meta' => [
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
                'total' => $applications->total(),
            ],
            'message' => null,
        ]);
    }

    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $this->authorize('create', Application::class);

        $application = Application::create([
            'startup_id' => $request->user()->id,
            'incubator_id' => $request->incubator_id,
            'motivation' => $request->motivation,
            'pitch_deck_url' => $request->pitch_deck_url,
            'status' => ApplicationStatus::Pending,
        ]);

        ActivityLog::record('application.submitted', $application);

        return response()->json([
            'data' => new ApplicationResource($application->load(['startup', 'incubator'])),
            'meta' => null,
            'message' => 'Application submitted successfully.',
        ], 201);
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): JsonResponse
    {
        $this->authorize('updateStatus', $application);

        $oldStatus = $application->status;

        $application->update([
            'status' => $request->status,
            'reviewer_notes' => $request->reviewer_notes ?? $application->reviewer_notes,
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
        ]);

        ActivityLog::record('application.status_changed', $application, [
            'from' => $oldStatus->value,
            'to' => $request->status,
        ]);

        $application->startup->notify(new ApplicationStatusChanged($application));

        return response()->json([
            'data' => new ApplicationResource($application->fresh(['startup', 'incubator'])),
            'meta' => null,
            'message' => 'Application status updated.',
        ]);
    }
}
