<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum SuggestionCategory: string implements HasLabel, HasColor, HasIcon
{
    case INFRASTRUCTURE = 'infrastruktura';
    case IT = 'it';
    case SECURITY = 'biztonsag';
    case ENVIRONMENT = 'kornyezet';
    case PROCESSES = 'folyamatok';
    case WORK_CONDITIONS = 'munkakorulmenyek';
    case COMMUNICATION = 'kommunikacio';
    case OTHER = 'egyeb';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INFRASTRUCTURE => __('suggestion_categories.infrastruktura'),
            self::IT => __('suggestion_categories.it'),
            self::SECURITY => __('suggestion_categories.biztonsag'),
            self::ENVIRONMENT => __('suggestion_categories.kornyezet'),
            self::PROCESSES => __('suggestion_categories.folyamatok'),
            self::WORK_CONDITIONS => __('suggestion_categories.munkakorulmenyek'),
            self::COMMUNICATION => __('suggestion_categories.kommunikacio'),
            self::OTHER => __('suggestion_categories.egyeb'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::INFRASTRUCTURE => 'amber',
            self::IT => 'blue',
            self::SECURITY => 'red',
            self::ENVIRONMENT => 'green',
            self::PROCESSES => 'purple',
            self::WORK_CONDITIONS => 'orange',
            self::COMMUNICATION => 'cyan',
            self::OTHER => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::INFRASTRUCTURE => 'heroicon-o-building-office',
            self::IT => 'heroicon-o-computer-desktop',
            self::SECURITY => 'heroicon-o-shield-check',
            self::ENVIRONMENT => 'heroicon-o-globe-europe-africa',
            self::PROCESSES => 'heroicon-o-cog-6-tooth',
            self::WORK_CONDITIONS => 'heroicon-o-user-group',
            self::COMMUNICATION => 'heroicon-o-chat-bubble-left-right',
            self::OTHER => 'heroicon-o-ellipsis-horizontal-circle',
        };
    }
}
