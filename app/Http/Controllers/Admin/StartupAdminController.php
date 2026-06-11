<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StartupProfile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StartupAdminController extends Controller
{
    public function index(Request $request): Response
    {
        $query = StartupProfile::with('user')->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        return Inertia::render('Admin/Startups', [
            'startups' => $query->paginate(20)->withQueryString(),
        ]);
    }
}
