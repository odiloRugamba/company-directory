<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Incubator = 'incubator';
    case Startup = 'startup';

    public function label(): string
    {
        return match($this) {
            Role::Admin => 'Administrator',
            Role::Incubator => 'Incubator',
            Role::Startup => 'Startup',
        };
    }
}
