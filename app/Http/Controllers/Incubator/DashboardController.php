<?php

namespace App\Http\Controllers\Incubator;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user()->load('incubatorProfile');
        $profile = $user->incubatorProfile;

        $applications = $user->applicationsAsIncubator()
            ->with('startup.startupProfile')
            ->get();

        $filled = collect([
            $profile?->organization_name,
            $profile?->description,
            $profile?->country,
            $profile?->city,
            $profile?->focus_sectors,
            $profile?->supported_stages,
            $profile?->capacity,
            $profile?->website,
        ])->filter()->count();

        return Inertia::render('Incubator/Dashboard', [
            'stats' => [
                'total_received' => $applications->count(),
                'pending' => $applications->where('status', ApplicationStatus::Pending)->count(),
                'accepted' => $applications->where('status', ApplicationStatus::Accepted)->count(),
                'completeness' => (int) round($filled / 8 * 100),
            ],
            'profile' => $profile,
        ]);
    }
}
