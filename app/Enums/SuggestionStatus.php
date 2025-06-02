<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum SuggestionStatus: string implements HasLabel, HasColor, HasIcon
{
    case SUBMITTED = 'submitted';
    case UNDER_REVIEW = 'under_review';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SUBMITTED => __('suggestion_statuses.submitted'),
            self::UNDER_REVIEW => __('suggestion_statuses.under_review'),
            self::IN_PROGRESS => __('suggestion_statuses.in_progress'),
            self::COMPLETED => __('suggestion_statuses.completed'),
            self::REJECTED => __('suggestion_statuses.rejected'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SUBMITTED => 'gray',
            self::UNDER_REVIEW => 'warning',
            self::IN_PROGRESS => 'primary',
            self::COMPLETED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::SUBMITTED => 'heroicon-o-inbox',
            self::UNDER_REVIEW => 'heroicon-o-eye',
            self::IN_PROGRESS => 'heroicon-o-arrow-path',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::REJECTED => 'heroicon-o-x-circle',
        };
    }
}
