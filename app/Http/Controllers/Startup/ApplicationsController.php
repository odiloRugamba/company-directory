<?php

namespace App\Http\Controllers\Startup;

use App\Http\Controllers\Controller;
use App\Models\IncubatorProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationsController extends Controller
{
    public function index(Request $request): Response
    {
        $applications = $request->user()
            ->applicationsAsStartup()
            ->with('incubator.incubatorProfile')
            ->latest()
            ->get();

        return Inertia::render('Startup/Applications', [
            'applications' => $applications,
        ]);
    }

    public function create(Request $request, string $slug): Response|RedirectResponse
    {
        $incubator = IncubatorProfile::where('slug', $slug)->with('user')->firstOrFail();

        $hasApplied = $request->user()
            ->applicationsAsStartup()
            ->where('incubator_id', $incubator->user_id)
            ->exists();

        if ($hasApplied) {
            return redirect()->route('startup.applications')
                ->with('error', 'You have already applied to this incubator.');
        }

        return Inertia::render('Startup/Apply', [
            'incubator' => $incubator,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'incubator_id' => ['required', 'integer', 'exists:users,id'],
            'motivation' => ['required', 'string', 'min:100'],
            'pitch_deck_url' => ['nullable', 'url'],
        ]);

        $exists = $request->user()->applicationsAsStartup()
            ->where('incubator_id', $data['incubator_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['incubator_id' => 'You have already applied to this incubator.']);
        }

        $request->user()->applicationsAsStartup()->create($data);

        return redirect()->route('startup.applications')->with('success', 'Application submitted!');
    }
}
