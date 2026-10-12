<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder;

use PageBuilder\FilamentPageBuilder\Blocks\ButtonBlock;
use PageBuilder\FilamentPageBuilder\Blocks\DividerBlock;
use PageBuilder\FilamentPageBuilder\Blocks\EmbedBlock;
use PageBuilder\FilamentPageBuilder\Blocks\HeadingBlock;
use PageBuilder\FilamentPageBuilder\Blocks\ImageBlock;
use PageBuilder\FilamentPageBuilder\Blocks\SectionBlock;
use PageBuilder\FilamentPageBuilder\Blocks\SpacerBlock;
use PageBuilder\FilamentPageBuilder\Blocks\TextBlock;
use PageBuilder\FilamentPageBuilder\Console\RegeneratePagesCommand;
use PageBuilder\FilamentPageBuilder\Console\TailwindSafelistCommand;
use PageBuilder\FilamentPageBuilder\Support\BlockRegistry;
use PageBuilder\FilamentPageBuilder\Support\HtmlExporter;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPageBuilderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-page-builder')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_pages_table')
            ->hasRoutes('api')
            ->hasCommands([
                RegeneratePagesCommand::class,
                TailwindSafelistCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(BlockRegistry::class, function () {
            $registry = new BlockRegistry;
            $registry->register([
                SectionBlock::class,
                HeadingBlock::class,
                TextBlock::class,
                ImageBlock::class,
                ButtonBlock::class,
                EmbedBlock::class,
                SpacerBlock::class,
                DividerBlock::class,
            ]);

            $extra = config('filament-page-builder.blocks', []);
            if (is_array($extra) && $extra !== []) {
                $registry->register($extra);
            }

            return $registry;
        });

        $this->app->singleton(HtmlExporter::class, function ($app) {
            return new HtmlExporter($app->make(BlockRegistry::class));
        });
    }
}
