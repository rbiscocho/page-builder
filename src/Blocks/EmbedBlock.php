<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Blocks;

use PageBuilder\FilamentPageBuilder\Contracts\Block;
use PageBuilder\FilamentPageBuilder\Support\Escaper;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

final class EmbedBlock implements Block
{
    private const ALLOWED_HOSTS = [
        'youtube.com',
        'www.youtube.com',
        'youtu.be',
        'player.vimeo.com',
        'vimeo.com',
        'www.google.com',
        'maps.google.com',
        'www.youtube-nocookie.com',
    ];

    public static function type(): string
    {
        return 'embed';
    }

    public static function label(): string
    {
        return 'Embed';
    }

    public static function icon(): ?string
    {
        return 'heroicon-o-code-bracket';
    }

    public static function defaults(): array
    {
        return [
            'url' => '',
            'padding_y' => 'none',
        ];
    }

    public static function schema(): array
    {
        return [];
    }

    public static function toHtml(array $data, string $childrenHtml = ''): string
    {
        $url = self::safeEmbedUrl((string) ($data['url'] ?? ''));
        if ($url === null) {
            return '';
        }

        $paddingY = TokenMap::resolve('padding_y', $data['padding_y'] ?? 'none');
        $wrapperClasses = trim(implode(' ', array_filter([$paddingY, 'aspect-video w-full overflow-hidden rounded-lg'])));

        return '<div class="'.Escaper::e($wrapperClasses).'">'
            .'<iframe src="'.Escaper::e($url).'" class="w-full h-full" '
            .'frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" '
            .'allowfullscreen loading="lazy" title="Embedded content"></iframe>'
            .'</div>';
    }

    public static function isEmailSafe(): bool
    {
        return false;
    }

    private static function safeEmbedUrl(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        $parts = parse_url($url);
        if ($parts === false || empty($parts['host'])) {
            return null;
        }

        $host = strtolower($parts['host']);
        $allowed = false;
        foreach (self::ALLOWED_HOSTS as $suffix) {
            if ($host === $suffix || str_ends_with($host, '.'.$suffix)) {
                $allowed = true;
                break;
            }
        }

        if (! $allowed) {
            return null;
        }

        $scheme = 'https';
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) ? '?'.$parts['query'] : '';

        if ($host === 'youtu.be' && $path !== '') {
            $id = ltrim($path, '/');

            return "https://www.youtube.com/embed/{$id}";
        }

        if ((str_contains($host, 'youtube.com')) && isset($parts['query'])) {
            parse_str($parts['query'], $q);
            if (! empty($q['v'])) {
                return 'https://www.youtube.com/embed/'.urlencode($q['v']);
            }
        }

        return "{$scheme}://{$host}{$path}{$query}";
    }
}
