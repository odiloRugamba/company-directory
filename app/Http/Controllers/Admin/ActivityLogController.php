<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActivityLogController extends Controller
{
    public function index(Request $request): Response
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        return Inertia::render('Admin/ActivityLog', [
            'logs' => $query->paginate(25)->withQueryString(),
            'actions' => ActivityLog::distinct('action')->pluck('action')->sort()->values(),
        ]);
    }
}
