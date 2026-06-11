<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\IncubatorProfile;
use App\Models\Sector;
use App\Models\StartupProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DirectoryController extends Controller
{
    public function index(Request $request): Response
    {
        $tab = $request->get('tab', 'incubators');
        $sectors = Sector::orderBy('name')->get(['name', 'slug', 'icon']);

        if ($tab === 'startups') {
            $query = StartupProfile::with('user');
            if ($request->filled('country')) $query->byCountry($request->country);
            if ($request->filled('sector')) $query->bySector($request->sector);
            if ($request->filled('stage')) $query->byStage($request->stage);
            if ($request->filled('search')) $query->search($request->search);
            $items = $query->latest()->paginate(12)->withQueryString();
        } else {
            $query = IncubatorProfile::with('user');
            if ($request->filled('country')) $query->byCountry($request->country);
            if ($request->filled('sector')) $query->bySector($request->sector);
            if ($request->filled('verified')) $query->verified();
            if ($request->filled('search')) $query->search($request->search);
            $items = $query->latest()->paginate(12)->withQueryString();
        }

        return Inertia::render('Public/Directory', [
            'items' => $items,
            'tab' => $tab,
            'sectors' => $sectors,
            'filters' => $request->only(['country', 'sector', 'stage', 'search', 'verified']),
        ]);
    }

    public function showIncubator(Request $request, string $slug): Response
    {
        $incubator = IncubatorProfile::with('user')->where('slug', $slug)->firstOrFail();

        $acceptedStartups = \App\Models\Application::where('incubator_id', $incubator->user_id)
            ->where('status', 'accepted')
            ->with('startup.startupProfile')
            ->get()
            ->pluck('startup');

        $hasApplied = false;
        $canApply = false;

        if ($user = $request->user()) {
            if ($user->isStartup()) {
                $hasApplied = $user->applicationsAsStartup()
                    ->where('incubator_id', $incubator->user_id)
                    ->exists();
                $canApply = !$hasApplied && $user->hasProfile();
            }
        }

        return Inertia::render('Public/IncubatorDetail', [
            'incubator' => $incubator,
            'acceptedStartups' => $acceptedStartups,
            'hasApplied' => $hasApplied,
            'canApply' => $canApply,
        ]);
    }

    public function showStartup(string $slug): Response
    {
        $startup = StartupProfile::with('user')->where('slug', $slug)->firstOrFail();

        return Inertia::render('Public/StartupDetail', [
            'startup' => $startup,
        ]);
    }
}
