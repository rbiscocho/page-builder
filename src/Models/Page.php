<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use PageBuilder\FilamentPageBuilder\Support\HtmlExporter;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'title',
        'slug',
        'design',
        'html',
        'published_html',
        'status',
        'published_at',
        'generated_at',
        'exporter_version',
    ];

    protected $casts = [
        'design' => 'array',
        'published_at' => 'datetime',
        'generated_at' => 'datetime',
    ];

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_html !== null;
    }

    public function regenerateHtml(HtmlExporter $exporter): void
    {
        $design = $this->design ?? [];
        $this->html = $exporter->export($design);
        $this->generated_at = now();
        $this->exporter_version = $exporter->version();
    }

    public function publish(): void
    {
        $this->published_html = $this->html;
        $this->status = 'published';
        $this->published_at = now();
    }

    public function unpublish(): void
    {
        $this->status = 'draft';
        $this->published_at = null;
    }
}
