<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \PageBuilder\FilamentPageBuilder\Models\Page */
class PageResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'html' => $this->published_html,
            'updated_at' => $this->updated_at?->toIso8601String(),
            'exporter_version' => $this->exporter_version,
        ];
    }
}
