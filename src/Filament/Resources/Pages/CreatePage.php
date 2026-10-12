<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Filament\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;
use PageBuilder\FilamentPageBuilder\Filament\Resources\PageResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function getRedirectUrl(): string
    {
        return PageResource::getUrl('design', ['record' => $this->getRecord()]);
    }
}
