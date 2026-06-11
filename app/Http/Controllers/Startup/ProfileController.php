<?php

namespace App\Http\Controllers\Startup;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Sector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Startup/Profile', [
            'profile' => $request->user()->startupProfile,
            'sectors' => Sector::orderBy('name')->get(['name', 'slug']),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = $request->user()->startupProfile;
        $this->authorize('update', $profile);

        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'country' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'website' => ['nullable', 'url'],
            'sector' => ['required', 'string'],
            'stage' => ['required', Rule::in(['idea', 'mvp', 'early_traction', 'growth', 'scale'])],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'team_size' => ['required', 'integer', 'min:1'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos/startups', 'public');
        } else {
            unset($data['logo']);
        }

        $data['slug'] = Str::slug($data['company_name']);
        $profile->update($data);
        ActivityLog::record('startup.profile_updated', $profile);

        return back()->with('success', 'Profile updated.');
    }
}
