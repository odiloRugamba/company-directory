<?php

namespace App\Http\Controllers\Startup;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $applications = $user->applicationsAsStartup()->with('incubator.incubatorProfile')->get();

        return Inertia::render('Startup/Dashboard', [
            'stats' => [
                'total' => $applications->count(),
                'accepted' => $applications->where('status', ApplicationStatus::Accepted)->count(),
                'pending' => $applications->where('status', ApplicationStatus::Pending)->count(),
                'rejected' => $applications->where('status', ApplicationStatus::Rejected)->count(),
            ],
            'hasApplications' => $applications->isNotEmpty(),
        ]);
    }
}
