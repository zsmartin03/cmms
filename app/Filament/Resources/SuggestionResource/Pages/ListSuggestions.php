<?php

namespace App\Filament\Resources\SuggestionResource\Pages;

use App\Filament\Resources\SuggestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListSuggestions extends ListRecords
{
    protected static string $resource = SuggestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\ActionGroup::make([
                Actions\Action::make('show_problems')
                    ->label('Problémák')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->action(function () {
                        $this->tableFilters['type']['value'] = 'problem';
                    }),

                Actions\Action::make('show_ideas')
                    ->label('Ötletek')
                    ->icon('heroicon-o-light-bulb')
                    ->color('success')
                    ->action(function () {
                        $this->tableFilters['type']['value'] = 'idea';
                    }),

                Actions\Action::make('show_pending')
                    ->label('Várakozó')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->action(function () {
                        $this->tableFilters['status']['value'] = 'submitted';
                    }),

                Actions\Action::make('clear_filters')
                    ->label('Szűrők törlése')
                    ->icon('heroicon-o-x-mark')
                    ->color('gray')
                    ->action(function () {
                        $this->tableFilters = [];
                    }),
            ])
                ->label('Gyors szűrők')
                ->icon('heroicon-o-funnel'),
        ];
    }

    public function vote($suggestionId, $voteType)
    {
        $suggestion = \App\Models\Suggestion::find($suggestionId);
        $user = auth()->user();

        if (!$suggestion) {
            Notification::make()
                ->title('Hiba!')
                ->body('A javaslat nem található.')
                ->danger()
                ->send();
            return;
        }

        // Ha már szavazott ugyanarra a típusra, akkor visszavonja
        if ($suggestion->hasUserVoted($user) && $suggestion->getUserVoteType($user) === $voteType) {
            $suggestion->removeVote($user);

            Notification::make()
                ->title('Szavazat visszavonva')
                ->body('Már nem szavazol erre a javaslatra.')
                ->info()
                ->duration(2000)
                ->send();
            return;
        }

        $suggestion->vote($user, $voteType);

        $message = $voteType === 'up' ? 'Támogatod ezt a javaslatot!' : 'Nem támogatod ezt a javaslatot.';
        $color = $voteType === 'up' ? 'success' : 'warning';

        Notification::make()
            ->title('Szavazat rögzítve')
            ->body($message)
            ->$color()
            ->duration(2000)
            ->send();
    }
}
