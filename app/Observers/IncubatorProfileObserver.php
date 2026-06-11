<?php

namespace App\Observers;

use App\Models\IncubatorProfile;
use Illuminate\Support\Facades\Cache;

class IncubatorProfileObserver
{
    public function saved(IncubatorProfile $profile): void
    {
        $this->clearCache();
    }

    public function deleted(IncubatorProfile $profile): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        try {
            $prefix = config('cache.prefix');
            $redis = Cache::getRedis();
            $keys = $redis->keys("{$prefix}directory:incubators:*");
            foreach ($keys as $key) {
                $redis->del($key);
            }
        } catch (\Throwable) {
            // Non-Redis cache driver (e.g., array in tests) — nothing to flush
        }
    }
}
