<?php

namespace App\Filament\Resources\SuggestionResource\Pages;

use App\Filament\Resources\SuggestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewSuggestion extends ViewRecord
{
    protected static string $resource = SuggestionResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('fields.basic_information'))
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label(__('fields.title')),

                        Infolists\Components\TextEntry::make('description')
                            ->label(__('fields.description'))
                            ->html(),

                        Infolists\Components\TextEntry::make('type')
                            ->label(__('fields.type'))
                            ->badge(),

                        Infolists\Components\TextEntry::make('category')
                            ->label(__('fields.category'))
                            ->badge()
                            ->placeholder(__('fields.none'))
                            ->visible(fn($record) => !empty($record->category)),

                        Infolists\Components\TextEntry::make('location')
                            ->label(__('fields.location'))
                            ->placeholder(__('fields.none'))
                            ->visible(fn($record) => !empty($record->location)),
                        Infolists\Components\TextEntry::make('priority')
                            ->label(__('fields.priority'))
                            ->badge(),

                        Infolists\Components\TextEntry::make('status')
                            ->label(__('fields.status'))
                            ->badge(),

                        Infolists\Components\TextEntry::make('assignedTo.name')
                            ->label(__('fields.assigned_to'))
                            ->placeholder(__('fields.none'))
                            ->visible(fn($record) => !empty($record->assigned_to)),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make(__('fields.admin_notes'))
                    ->schema([
                        Infolists\Components\TextEntry::make('admin_notes')
                            ->label(__(''))
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->visible(
                        fn($record) =>
                        auth()->user()?->hasRole(['admin', 'repairer']) &&
                            !empty($record->admin_notes)
                    ),

                Infolists\Components\Section::make(__('fields.votes'))
                    ->schema([
                        Infolists\Components\TextEntry::make('votes_up')
                            ->label(__('fields.votes_up'))
                            ->numeric(),

                        Infolists\Components\TextEntry::make('votes_down')
                            ->label(__('fields.votes_down'))
                            ->numeric(),

                        Infolists\Components\TextEntry::make('total_votes')
                            ->label(__('fields.total_votes'))
                            ->state(fn($record) => $record->votes_up + $record->votes_down),
                    ])
                    ->columns(3)
                    ->visible(fn($record) => $record->votes_up > 0 || $record->votes_down > 0),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \Coolsam\NestedComments\Filament\Widgets\CommentsWidget::class,
        ];
    }
}
