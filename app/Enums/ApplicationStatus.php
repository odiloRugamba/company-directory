<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Pending = 'pending';
    case UnderReview = 'under_review';
    case Accepted = 'accepted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match($this) {
            ApplicationStatus::Pending => 'Pending',
            ApplicationStatus::UnderReview => 'Under Review',
            ApplicationStatus::Accepted => 'Accepted',
            ApplicationStatus::Rejected => 'Rejected',
        };
    }
}
