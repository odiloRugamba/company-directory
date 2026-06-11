<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user()?->load('incubatorProfile', 'startupProfile');

        return [
            ...parent::share($request),
            'auth' => ['user' => $user],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],
            'notifications' => fn() => $user?->unreadNotifications->take(5),
            'unreadCount' => fn() => $user?->unreadNotifications->count() ?? 0,
        ];
    }
}
