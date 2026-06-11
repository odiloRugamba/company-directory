<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\IncubatorProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class IncubatorAdminController extends Controller
{
    public function index(Request $request): Response
    {
        $query = IncubatorProfile::with('user')->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        return Inertia::render('Admin/Incubators', [
            'incubators' => $query->paginate(20)->withQueryString(),
        ]);
    }

    public function verify(IncubatorProfile $incubator): RedirectResponse
    {
        $incubator->update(['is_verified' => !$incubator->is_verified]);

        $action = $incubator->is_verified ? 'incubator.verified' : 'incubator.unverified';
        ActivityLog::record($action, $incubator);

        return back()->with('success', $incubator->is_verified ? 'Incubator verified.' : 'Verification removed.');
    }

    public function deactivate(User $user): RedirectResponse
    {
        $user->update(['is_active' => !$user->is_active]);
        ActivityLog::record($user->is_active ? 'user.activated' : 'user.deactivated', $user);

        return back()->with('success', 'User status updated.');
    }
}
