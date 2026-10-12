<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use PageBuilder\FilamentPageBuilder\Http\Resources\PageListResource;
use PageBuilder\FilamentPageBuilder\Http\Resources\PageResource;
use PageBuilder\FilamentPageBuilder\Models\Page;

class PageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $pages = Cache::remember('page-builder:pages-list', 300, function () {
            return Page::query()
                ->where('status', 'published')
                ->orderBy('title')
                ->get(['slug', 'title', 'updated_at']);
        });

        return PageListResource::collection($pages)
            ->response()
            ->header('Cache-Control', 'public, max-age=60');
    }

    public function show(Request $request, string $slug): JsonResponse|Response
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $page || $page->published_html === null) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $etag = '"'.md5(($page->updated_at?->timestamp ?? 0).'|'.($page->exporter_version ?? '')).'"';
        $lastModified = $page->updated_at?->toRfc7231String();

        if ($request->header('If-None-Match') === $etag) {
            return response('', 304)
                ->header('ETag', $etag)
                ->header('Cache-Control', 'public, max-age=60');
        }

        if ($lastModified && $request->header('If-Modified-Since') === $lastModified) {
            return response('', 304)
                ->header('Last-Modified', $lastModified)
                ->header('Cache-Control', 'public, max-age=60');
        }

        return (new PageResource($page))
            ->response()
            ->header('ETag', $etag)
            ->header('Last-Modified', $lastModified ?? '')
            ->header('Cache-Control', 'public, max-age=60');
    }
}
