<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PurchaseOrderStatus: string implements HasLabel, HasColor
{
    case Draft = 'draft';
    case Pending = 'pending';
    case Ordered = 'ordered';
    case Received = 'received';
    case Cancelled = 'cancelled';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Pending => 'warning',
            self::Ordered => 'primary',
            self::Received => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Pending => 'Pending',
            self::Ordered => 'Ordered',
            self::Received => 'Received',
            self::Cancelled => 'Cancelled',
        };
    }

    public static function getColorsAsKeys(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $case) => [$case->getColor() => $case->value])
            ->toArray();
    }
}
