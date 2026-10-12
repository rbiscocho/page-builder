<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Filament\Resources;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use PageBuilder\FilamentPageBuilder\Filament\Resources\Pages\CreatePage;
use PageBuilder\FilamentPageBuilder\Filament\Resources\Pages\DesignPage as ResourceDesignPage;
use PageBuilder\FilamentPageBuilder\Filament\Resources\Pages\EditPage;
use PageBuilder\FilamentPageBuilder\Filament\Resources\Pages\ListPages;
use PageBuilder\FilamentPageBuilder\Models\Page;
use PageBuilder\FilamentPageBuilder\Support\HtmlExporter;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Pages';

    protected static ?string $modelLabel = 'Page';

    protected static ?string $pluralModelLabel = 'Pages';

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, ?string $state): void {
                        if (filled($state)) {
                            $set('slug', str($state)->slug()->toString());
                        }
                    }),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->copyable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        default => 'warning',
                    }),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('design')
                    ->label('Design')
                    ->icon('heroicon-o-paint-brush')
                    ->url(fn (Page $record): string => static::getUrl('design', ['record' => $record])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
            'design' => ResourceDesignPage::route('/{record}/design'),
        ];
    }

    public static function regenerateHtml(Page $record): void
    {
        $record->regenerateHtml(app(HtmlExporter::class));
        $record->save();
    }
}
