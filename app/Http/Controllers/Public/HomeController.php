<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\IncubatorProfile;
use App\Models\Sector;
use App\Models\StartupProfile;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Public/Home', [
            'totalIncubators' => IncubatorProfile::count(),
            'totalStartups' => StartupProfile::count(),
            'totalCountries' => IncubatorProfile::distinct('country')->count('country'),
            'sectors' => Sector::orderBy('name')->get(['name', 'slug', 'icon']),
            'featuredIncubators' => IncubatorProfile::with('user')
                ->withCount(['user as applications_received' => fn($q) => $q->whereHas('applicationsAsIncubator')])
                ->verified()
                ->orderByDesc('applications_received')
                ->take(6)
                ->get(),
        ]);
    }
}
