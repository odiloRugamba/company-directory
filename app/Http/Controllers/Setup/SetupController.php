<?php

namespace App\Http\Controllers\Setup;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\IncubatorProfile;
use App\Models\Sector;
use App\Models\StartupProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SetupController extends Controller
{
    public function show(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasProfile()) {
            return redirect()->route($this->dashboardRoute($user->role));
        }

        return Inertia::render('Auth/Setup', [
            'sectors' => Sector::orderBy('name')->get(['name', 'slug']),
        ]);
    }

    public function storeIncubator(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->role === Role::Incubator, 403);

        $data = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50'],
            'country' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'website' => ['nullable', 'url'],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'capacity' => ['required', 'integer', 'min:1', 'max:1000'],
            'focus_sectors' => ['required', 'array', 'min:1'],
            'supported_stages' => ['required', 'array', 'min:1'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        $slug = $this->uniqueSlug(Str::slug($data['organization_name']), 'incubator_profiles');

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos/incubators', 'public');
        }

        $profile = IncubatorProfile::create([
            ...$data,
            'user_id' => $user->id,
            'slug' => $slug,
            'logo' => $logoPath,
        ]);

        ActivityLog::record('incubator.profile_created', $profile);

        return redirect()->route('incubator.dashboard')->with('success', 'Profile created! Welcome to SANIA.');
    }

    public function storeStartup(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user->role === Role::Startup, 403);

        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:50'],
            'country' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'website' => ['nullable', 'url'],
            'sector' => ['required', 'string'],
            'stage' => ['required', Rule::in(['idea', 'mvp', 'early_traction', 'growth', 'scale'])],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'team_size' => ['required', 'integer', 'min:1'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        $slug = $this->uniqueSlug(Str::slug($data['company_name']), 'startup_profiles');

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos/startups', 'public');
        }

        $profile = StartupProfile::create([
            ...$data,
            'user_id' => $user->id,
            'slug' => $slug,
            'logo' => $logoPath,
        ]);

        ActivityLog::record('startup.profile_created', $profile);

        return redirect()->route('startup.dashboard')->with('success', 'Profile created! Welcome to SANIA.');
    }

    private function dashboardRoute(Role $role): string
    {
        return match($role) {
            Role::Admin => 'admin.dashboard',
            Role::Incubator => 'incubator.dashboard',
            Role::Startup => 'startup.dashboard',
        };
    }

    private function uniqueSlug(string $base, string $table): string
    {
        $slug = $base;
        $i = 1;
        while (\DB::table($table)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
