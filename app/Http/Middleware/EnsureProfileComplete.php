<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && !$user->hasProfile()) {
            if ($request->route()?->getName() !== 'setup' && !str_starts_with($request->path(), 'setup')) {
                return redirect()->route('setup');
            }
        }

        return $next($request);
    }
}
