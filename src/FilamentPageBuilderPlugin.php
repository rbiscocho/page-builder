<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder;

use Filament\Contracts\Plugin;
use Filament\Panel;
use PageBuilder\FilamentPageBuilder\Filament\Resources\PageResource;

/**
 * Filament panel plugin (Filament 5.x Plugin contract).
 *
 *   $panel->plugin(
 *       FilamentPageBuilderPlugin::make()
 *           ->resource(true) // register package PageResource (default)
 *   );
 *
 * Design canvas is a resource page on PageResource — see docs/canvas-setup.md.
 *
 * @see https://filamentphp.com/docs/5.x/plugins/panel-plugins
 */
class FilamentPageBuilderPlugin implements Plugin
{
    protected bool $hasResource = true;

    /**
     * Override the resource class registered on the panel.
     *
     * @var class-string<\Filament\Resources\Resource>|null
     */
    protected ?string $resourceClass = null;

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'filament-page-builder';
    }

    /**
     * Register the package (or custom) PageResource on the panel.
     */
    public function resource(bool $condition = true): static
    {
        $this->hasResource = $condition;

        return $this;
    }

    public function hasResource(): bool
    {
        return $this->hasResource;
    }

    /**
     * @param  class-string<\Filament\Resources\Resource>  $resource
     */
    public function usingResource(string $resource): static
    {
        $this->resourceClass = $resource;
        $this->hasResource = true;

        return $this;
    }

    /**
     * @return class-string<\Filament\Resources\Resource>
     */
    public function getResourceClass(): string
    {
        return $this->resourceClass ?? PageResource::class;
    }

    /** @deprecated Use usingResource() */
    public function pageResource(string $resource): static
    {
        return $this->usingResource($resource);
    }

    /** @deprecated Use getResourceClass() */
    public function getPageResource(): ?string
    {
        return $this->hasResource ? $this->getResourceClass() : null;
    }

    public function register(Panel $panel): void
    {
        if ($this->hasResource()) {
            $panel->resources([
                $this->getResourceClass(),
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        // Panel-scoped runtime hooks go here (runs only when this panel is active).
    }
}
