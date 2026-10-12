<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Blocks;

use PageBuilder\FilamentPageBuilder\Contracts\Block;
use PageBuilder\FilamentPageBuilder\Support\Escaper;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

final class ImageBlock implements Block
{
    public static function type(): string
    {
        return 'image';
    }

    public static function label(): string
    {
        return 'Image';
    }

    public static function icon(): ?string
    {
        return 'heroicon-o-photo';
    }

    public static function defaults(): array
    {
        return [
            'src' => '',
            'alt' => '',
            'width' => 'full',
            'padding_y' => 'none',
        ];
    }

    public static function schema(): array
    {
        return [];
    }

    public static function toHtml(array $data, string $childrenHtml = ''): string
    {
        $src = self::absoluteUrl((string) ($data['src'] ?? ''));
        $alt = Escaper::e((string) ($data['alt'] ?? ''));
        $width = TokenMap::resolve('width', $data['width'] ?? 'full');
        $paddingY = TokenMap::resolve('padding_y', $data['padding_y'] ?? 'none');

        $wrapperClasses = trim(implode(' ', array_filter([$width, $paddingY, 'overflow-hidden rounded-lg'])));
        $imgClasses = 'w-full h-auto object-cover';

        if ($src === '') {
            return '';
        }

        return '<div class="'.Escaper::e($wrapperClasses).'">'
            .'<img src="'.Escaper::e($src).'" alt="'.$alt.'" class="'.Escaper::e($imgClasses).'" loading="lazy" />'
            .'</div>';
    }

    public static function isEmailSafe(): bool
    {
        return true;
    }

    private static function absoluteUrl(string $path): string
    {
        if ($path === '') {
            return '';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }
        if (function_exists('asset')) {
            return asset($path);
        }
        $base = rtrim((string) (getenv('APP_URL') ?: ''), '/');

        return $base.'/'.ltrim($path, '/');
    }
}
