<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * Register on your Filament panel:
 *
 *   $panel->plugin(FilamentPageBuilderPlugin::make());
 *
 * The design canvas is a resource page — bind it in your app's PageResource
 * (see docs/canvas-setup.md). This plugin wires panel-level assets and
 * optional discovery hooks.
 */
class FilamentPageBuilderPlugin implements Plugin
{
    protected bool $registerAssets = true;

    /**
     * Optional FQCN of the app PageResource. When set, the panel can deep-link
     * to design routes from notifications / widgets later.
     *
     * @var class-string<\Filament\Resources\Resource>|null
     */
    protected ?string $pageResource = null;

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
     * @param  class-string<\Filament\Resources\Resource>  $resource
     */
    public function pageResource(string $resource): static
    {
        $this->pageResource = $resource;

        return $this;
    }

    public function getPageResource(): ?string
    {
        return $this->pageResource;
    }

    public function registerAssets(bool $condition = true): static
    {
        $this->registerAssets = $condition;

        return $this;
    }

    public function register(Panel $panel): void
    {
        // DesignPage is a Filament\Resources\Pages\Page and must be registered
        // on the app's PageResource via getPages(). No panel pages are added
        // here so the package stays resource-agnostic.
    }

    public function boot(Panel $panel): void
    {
        if ($this->registerAssets) {
            // Reserved for future canvas CSS/JS published via FilamentAsset.
            // Views currently use Tailwind utility classes from the host app.
        }
    }
}
