<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Filament\Resources\Pages;

use PageBuilder\FilamentPageBuilder\Filament\Pages\DesignPage as BaseDesignPage;
use PageBuilder\FilamentPageBuilder\Filament\Resources\PageResource;

/**
 * Resource-scoped design canvas (registered on PageResource::getPages()).
 */
class DesignPage extends BaseDesignPage
{
    protected static string $resource = PageResource::class;
}
