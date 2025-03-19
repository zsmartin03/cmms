<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use App\Filament\Resources\DocumentResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('fields.name'))
                    ->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->url(fn(): string =>
                DocumentResource::getUrl('create')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->url(fn(Model $record): string
                => DocumentResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make()->url(fn(Model $record): string
                => DocumentResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\Action::make('download')
                    ->label(__('actions.download'))
                    ->action(function ($record) {
                        return Storage::download('public/' . $record->attachment);
                    })
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary'),
                Tables\Actions\Action::make('QR')->label(__('fields.qr_code'))
                    ->modalContent(fn($record): View =>
                    view(
                        'filament.resources.document-resource.pages.q-r-document',
                        ['record' => $record]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->icon('heroicon-o-printer')
                    ->color('secondary')
                    ->tooltip(__('actions.print') . ': ' . __('fields.qr_code'))
            ])
            ->bulkActions([])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->url(fn(): string =>
                DocumentResource::getUrl('create')),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('module_names.documents.plural_label');
    }
    public static function getModelLabel(): string
    {
        return __('module_names.documents.label');
    }
}
