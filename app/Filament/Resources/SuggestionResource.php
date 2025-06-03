<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuggestionResource\Pages;
use App\Models\Suggestion;
use App\Enums\SuggestionType;
use App\Enums\SuggestionStatus;
use App\Enums\SuggestionPriority;
use App\Enums\SuggestionCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class SuggestionResource extends Resource
{
    protected static ?string $model = Suggestion::class;
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    public static function getNavigationGroup(): string
    {
        return __('module_names.navigation_groups.failure_report');
    }

    protected static ?int $navigationSort = 8;

    public static function getModelLabel(): string
    {
        return __('module_names.suggestions.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('module_names.suggestions.plural_label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('fields.basic_information'))->schema([
                    Forms\Components\TextInput::make('title')
                        ->label(__('fields.title'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('description')
                        ->label(__('fields.description'))
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\Select::make('type')
                        ->label(__('fields.type'))
                        ->options(SuggestionType::class)
                        ->default(SuggestionType::IDEA)
                        ->required(),

                    Forms\Components\Select::make('category')
                        ->label(__('fields.category'))
                        ->options(SuggestionCategory::class)
                        ->placeholder(__('fields.select_category')),

                    Forms\Components\TextInput::make('location')
                        ->label(__('fields.location')),

                    Forms\Components\Select::make('priority')
                        ->label(__('fields.priority'))
                        ->options(SuggestionPriority::class)
                        ->default(SuggestionPriority::NORMAL)
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label(__('fields.status'))
                        ->options(SuggestionStatus::class)
                        ->default(SuggestionStatus::SUBMITTED)
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $record) {
                            if ($record && $state === SuggestionStatus::COMPLETED) {
                                $record->update(['resolved_at' => now()]);
                            }
                        })
                        ->visible(function ($operation, $record) {
                            // Csak admin és repairer láthatja edit módban
                            if ($operation === 'edit') {
                                return auth()->user()?->hasAnyRole(['admin', 'repairer']);
                            }
                            // Create módban senki nem látja (automatikusan SUBMITTED lesz)
                            return false;
                        })
                        ->disabled(function ($operation) {
                            // Extra védelem: ha valahogy mégis látható lenne, akkor is letiltva operator számára
                            if ($operation === 'edit') {
                                return !auth()->user()?->hasAnyRole(['admin', 'repairer']);
                            }
                            return false;
                        }),
                    Forms\Components\Select::make('assigned_to')
                        ->label(__('fields.assigned_to'))
                        ->relationship('assignedTo', 'name')
                        ->searchable()
                        ->preload()
                        ->placeholder(__('fields.select_assignee'))
                        ->visible(fn() => auth()->user()?->hasRole(['admin', 'repairer'])),
                ]),

                Forms\Components\Section::make(__('fields.admin_notes'))
                    ->schema([
                        Forms\Components\RichEditor::make('admin_notes')
                            ->label(__(''))
                            ->columnSpanFull(),
                    ])
                    ->visible(
                        fn($record, $operation) =>
                        auth()->user()?->hasRole(['admin', 'repairer']) &&
                            ($operation === 'edit' || ($record && !empty($record->admin_notes)))
                    ),

                Forms\Components\Hidden::make('author_id')
                    ->default(auth()->id()),

                // Hidden field a status alapértékhez létrehozáskor
                Forms\Components\Hidden::make('status')
                    ->default(SuggestionStatus::SUBMITTED->value)
                    ->visible(fn($operation) => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\ViewColumn::make('votes')
                    ->label('')
                    ->view('filament.tables.columns.suggestion-votes')
                    ->width('80px')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('fields.title'))
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn($record) => 'S-' . str_pad($record->id, 6, '0', STR_PAD_LEFT) . ' • ' . $record->created_at->diffForHumans())
                    ->tooltip(function ($record) {
                        return $record->title . "\n\n" . __('fields.description') . ": " . strip_tags($record->description);
                    }),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('fields.type'))
                    ->badge()
                    ->size('sm'),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('fields.status'))
                    ->badge()
                    ->size('sm'),

                Tables\Columns\TextColumn::make('priority')
                    ->label(__('fields.priority'))
                    ->badge()
                    ->size('sm'),

                Tables\Columns\TextColumn::make('category')
                    ->label(__('fields.category'))
                    ->badge()
                    ->size('sm')
                    ->placeholder(__('fields.none'))
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('fields.type'))
                    ->options(SuggestionType::class),

                Tables\Filters\SelectFilter::make('status')
                    ->label(__('fields.status'))
                    ->options(SuggestionStatus::class),

                Tables\Filters\SelectFilter::make('priority')
                    ->label(__('fields.priority'))
                    ->options(SuggestionPriority::class),

                Tables\Filters\SelectFilter::make('category')
                    ->label(__('fields.category'))
                    ->options(SuggestionCategory::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->tooltip(__('fields.view_details')),

                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip(__('fields.edit'))
                    ->visible(function ($record) {
                        $user = auth()->user();
                        if ($user?->hasAnyRole(['admin', 'repairer'])) {
                            return true;
                        }

                        if ($user?->hasRole('operator') && $record) {
                            return $record->author_id === $user->id;
                        }

                        return false;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip(__('fields.delete'))
                    ->requiresConfirmation()
                    ->modalHeading(__('fields.delete_confirmation'))
                    ->modalDescription(__('fields.delete_confirmation_text'))
                    ->modalSubmitActionLabel(__('fields.delete'))
                    ->successNotificationTitle(__('fields.deleted_successfully'))
                    ->visible(function ($record) {
                        $user = auth()->user();

                        // Admin mindig törölhet
                        if ($user?->hasRole('admin')) {
                            return true;
                        }

                        // Operator csak a saját, még "submitted" státuszú javaslatait törölheti
                        if ($user?->hasRole('operator') && $record) {
                            return $record->author_id === $user->id &&
                                $record->status === \App\Enums\SuggestionStatus::SUBMITTED;
                        }

                        return false;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50])
            ->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuggestions::route('/'),
            'create' => Pages\CreateSuggestion::route('/create'),
            'view' => Pages\ViewSuggestion::route('/{record}'),
            'edit' => Pages\EditSuggestion::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', SuggestionStatus::SUBMITTED)->count();
    }
}
