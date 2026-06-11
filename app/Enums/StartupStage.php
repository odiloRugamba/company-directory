<?php

namespace App\Enums;

enum StartupStage: string
{
    case Idea = 'idea';
    case MVP = 'mvp';
    case EarlyTraction = 'early_traction';
    case Growth = 'growth';
    case Scale = 'scale';

    public function label(): string
    {
        return match($this) {
            StartupStage::Idea => 'Idea',
            StartupStage::MVP => 'MVP',
            StartupStage::EarlyTraction => 'Early Traction',
            StartupStage::Growth => 'Growth',
            StartupStage::Scale => 'Scale',
        };
    }

    public function description(): string
    {
        return match($this) {
            StartupStage::Idea => 'Pre-product, validating the problem',
            StartupStage::MVP => 'Built something, testing with first users',
            StartupStage::EarlyTraction => 'Paying customers, proving the model',
            StartupStage::Growth => 'Scaling what works',
            StartupStage::Scale => 'Expanding markets and team',
        };
    }
}
