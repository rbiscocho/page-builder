<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Filament\Resources\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use PageBuilder\FilamentPageBuilder\Filament\Resources\PageResource;
use PageBuilder\FilamentPageBuilder\Models\Page;
use PageBuilder\FilamentPageBuilder\Support\HtmlExporter;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('design')
                ->label('Design')
                ->icon('heroicon-o-paint-brush')
                ->url(fn (): string => PageResource::getUrl('design', ['record' => $this->getRecord()])),
            Action::make('publish')
                ->label('Publish')
                ->icon('heroicon-o-globe-alt')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (): void {
                    /** @var Page $record */
                    $record = $this->getRecord();
                    $record->regenerateHtml(app(HtmlExporter::class));
                    $record->publish();
                    $record->save();
                    $this->refreshFormData(['status']);
                }),
            DeleteAction::make(),
        ];
    }
}
