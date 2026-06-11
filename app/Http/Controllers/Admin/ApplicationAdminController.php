<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationAdminController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Application::with(['startup.startupProfile', 'incubator.incubatorProfile'])->latest();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        return Inertia::render('Admin/Applications', [
            'applications' => $query->paginate(20)->withQueryString(),
            'activeStatus' => $request->get('status'),
        ]);
    }
}
