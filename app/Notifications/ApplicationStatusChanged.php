<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Application $application) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->application->status->label();
        $incubatorName = $this->application->incubator->incubatorProfile?->organization_name ?? 'the incubator';

        return [
            'title' => "Application {$status}",
            'body' => "Your application to {$incubatorName} has been {$status}.",
            'link' => '/dashboard/applications',
            'application_id' => $this->application->id,
            'status' => $this->application->status->value,
        ];
    }
}
