<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use PageBuilder\FilamentPageBuilder\Http\Controllers\Api\PageController;

$middleware = [];

if (config('filament-page-builder.api_auth')) {
    $middleware[] = 'auth:sanctum';
}

Route::middleware($middleware)->group(function () {
    Route::get('pages', [PageController::class, 'index'])->name('page-builder.pages.index');
    Route::get('pages/{slug}', [PageController::class, 'show'])->name('page-builder.pages.show');
});

Route::get('page-builder/safelist.css', function () {
    $path = config('filament-page-builder.safelist_path', 'resources/css/page-builder-safelist.css');
    if (! str_starts_with($path, '/')) {
        $path = base_path($path);
    }
    if (! is_file($path)) {
        abort(404);
    }

    return response(file_get_contents($path), 200, [
        'Content-Type' => 'text/css; charset=UTF-8',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('page-builder.safelist');
