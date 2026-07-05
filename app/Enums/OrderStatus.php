<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasColor, HasLabel
{
    case New = 'new';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return __('shop.status.'.$this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::New => 'warning',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }
}
