<?php

declare(strict_types=1);

return [

    'blocks' => [
        // \App\PageBuilder\Blocks\HeroBlock::class,
    ],

    'cors_origin' => env('PAGE_BUILDER_CORS_ORIGIN', '*'),

    'api_auth' => env('PAGE_BUILDER_API_AUTH', false),

    'asset_url' => env('APP_URL', 'http://localhost'),

    'safelist_path' => env('PAGE_BUILDER_SAFELIST_PATH', 'resources/css/page-builder-safelist.css'),

];
