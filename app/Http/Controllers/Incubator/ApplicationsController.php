<?php

namespace App\Http\Controllers\Incubator;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ApplicationsController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        $applications = $user->applicationsAsIncubator()
            ->with('startup.startupProfile')
            ->latest()
            ->get()
            ->groupBy(fn($a) => $a->status->value);

        return Inertia::render('Incubator/Applications', [
            'columns' => [
                'pending' => $applications->get('pending', collect())->values(),
                'under_review' => $applications->get('under_review', collect())->values(),
                'decided' => collect([
                    ...$applications->get('accepted', collect()),
                    ...$applications->get('rejected', collect()),
                ])->sortByDesc('updated_at')->values(),
            ],
        ]);
    }
}
