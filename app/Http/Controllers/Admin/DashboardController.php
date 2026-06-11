<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\IncubatorProfile;
use App\Models\StartupProfile;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_incubators' => IncubatorProfile::count(),
                'total_startups' => StartupProfile::count(),
                'pending_applications' => Application::where('status', 'pending')->count(),
                'countries' => IncubatorProfile::distinct('country')->count('country'),
            ],
            'recent_applications' => Application::with(['startup.startupProfile', 'incubator.incubatorProfile'])
                ->latest()
                ->take(10)
                ->get(),
            'recent_registrations' => User::latest()->take(10)->get(),
        ]);
    }
}
