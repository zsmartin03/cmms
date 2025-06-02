<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum SuggestionPriority: int implements HasLabel, HasColor, HasIcon
{
    case LOW = 1;
    case NORMAL = 2;
    case HIGH = 3;
    case URGENT = 4;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LOW => __('suggestion_priorities.low'),
            self::NORMAL => __('suggestion_priorities.normal'),
            self::HIGH => __('suggestion_priorities.high'),
            self::URGENT => __('suggestion_priorities.urgent'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::LOW => 'success',
            self::NORMAL => 'primary',
            self::HIGH => 'warning',
            self::URGENT => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::LOW => 'heroicon-o-arrow-down',
            self::NORMAL => 'heroicon-o-minus',
            self::HIGH => 'heroicon-o-arrow-up',
            self::URGENT => 'heroicon-o-fire',
        };
    }
}
