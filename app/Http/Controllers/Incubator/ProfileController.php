<?php

namespace App\Http\Controllers\Incubator;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Sector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        $profile = $request->user()->incubatorProfile;

        return Inertia::render('Incubator/Profile', [
            'profile' => $profile,
            'sectors' => Sector::orderBy('name')->get(['name', 'slug']),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = $request->user()->incubatorProfile;
        $this->authorize('update', $profile);

        $data = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'country' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'website' => ['nullable', 'url'],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'capacity' => ['required', 'integer', 'min:1'],
            'focus_sectors' => ['required', 'array'],
            'supported_stages' => ['required', 'array'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos/incubators', 'public');
        } else {
            unset($data['logo']);
        }

        $data['slug'] = Str::slug($data['organization_name']);

        $profile->update($data);
        ActivityLog::record('incubator.profile_updated', $profile);

        return back()->with('success', 'Profile updated successfully.');
    }
}
