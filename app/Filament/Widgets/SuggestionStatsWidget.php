<?php

namespace App\Filament\Widgets;

use App\Models\Suggestion;
use App\Enums\SuggestionStatus;
use App\Enums\SuggestionType;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SuggestionStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $newSuggestions = Suggestion::where('status', SuggestionStatus::SUBMITTED)->count();
        $inProgress = Suggestion::where('status', SuggestionStatus::IN_PROGRESS)->count();
        $completedThisMonth = Suggestion::where('status', SuggestionStatus::COMPLETED)
            ->whereMonth('resolved_at', now()->month)
            ->count();
        $problems = Suggestion::where('type', SuggestionType::PROBLEM)
            ->active()
            ->count();

        return [
            Stat::make(__('widgets.suggestion_stats.new_suggestions'), $newSuggestions)
                ->description(__('widgets.suggestion_stats.new_suggestions_description'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('warning'),

            Stat::make(__('widgets.suggestion_stats.in_progress'), $inProgress)
                ->description(__('widgets.suggestion_stats.in_progress_description'))
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('primary'),

            Stat::make(__('widgets.suggestion_stats.completed_this_month'), $completedThisMonth)
                ->description(__('widgets.suggestion_stats.completed_this_month_description'))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make(__('widgets.suggestion_stats.open_problems'), $problems)
                ->description(__('widgets.suggestion_stats.open_problems_description'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
