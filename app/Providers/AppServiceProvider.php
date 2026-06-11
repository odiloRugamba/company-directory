<?php

namespace App\Providers;

use App\Models\IncubatorProfile;
use App\Models\StartupProfile;
use App\Models\Application;
use App\Observers\IncubatorProfileObserver;
use App\Policies\ApplicationPolicy;
use App\Policies\IncubatorProfilePolicy;
use App\Policies\StartupProfilePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        IncubatorProfile::observe(IncubatorProfileObserver::class);

        Gate::policy(Application::class, ApplicationPolicy::class);
        Gate::policy(IncubatorProfile::class, IncubatorProfilePolicy::class);
        Gate::policy(StartupProfile::class, StartupProfilePolicy::class);
    }
}
