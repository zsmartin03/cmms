<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum SuggestionType: string implements HasLabel, HasColor, HasIcon
{
    case PROBLEM = 'problem';
    case IDEA = 'idea';
    case COMPLAINT = 'complaint';
    case IMPROVEMENT = 'improvement';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PROBLEM => __('suggestion_types.problem'),
            self::IDEA => __('suggestion_types.idea'),
            self::COMPLAINT => __('suggestion_types.complaint'),
            self::IMPROVEMENT => __('suggestion_types.improvement'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PROBLEM => 'danger',
            self::IDEA => 'success',
            self::COMPLAINT => 'warning',
            self::IMPROVEMENT => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::PROBLEM => 'heroicon-o-exclamation-triangle',
            self::IDEA => 'heroicon-o-light-bulb',
            self::COMPLAINT => 'heroicon-o-chat-bubble-left-ellipsis',
            self::IMPROVEMENT => 'heroicon-o-arrow-trending-up',
        };
    }
}
