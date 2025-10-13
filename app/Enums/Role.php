<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Role: string implements HasLabel
{
    case Admin = 'admin';
    case Office = 'office';
    case Kitchen = 'kitchen';
    case DiningRoom = 'dining_room';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Office => 'Office Area',
            self::Kitchen => 'Kitchen Area',
            self::DiningRoom => 'Dining Room Area',
        };
    }
}
