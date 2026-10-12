<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Console;

use Illuminate\Console\Command;
use PageBuilder\FilamentPageBuilder\Models\Page;
use PageBuilder\FilamentPageBuilder\Support\HtmlExporter;

class RegeneratePagesCommand extends Command
{
    protected $signature = 'page-builder:regenerate {--all : Regenerate every page} {slug? : Single page slug}';

    protected $description = 'Rebuild html from design JSON after exporter changes';

    public function handle(HtmlExporter $exporter): int
    {
        $query = Page::query();

        if ($slug = $this->argument('slug')) {
            $query->where('slug', $slug);
        } elseif (! $this->option('all')) {
            $this->error('Pass a slug or --all');

            return self::FAILURE;
        }

        $count = 0;
        $query->orderBy('id')->chunkById(50, function ($pages) use ($exporter, &$count) {
            foreach ($pages as $page) {
                $page->regenerateHtml($exporter);
                $page->save();
                $count++;
                $this->line("Regenerated: {$page->slug}");
            }
        });

        $this->info("Done. Regenerated {$count} page(s). Exporter version: {$exporter->version()}");

        return self::SUCCESS;
    }
}
