<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Blocks;

use PageBuilder\FilamentPageBuilder\Contracts\Block;
use PageBuilder\FilamentPageBuilder\Support\Escaper;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

final class SpacerBlock implements Block
{
    public static function type(): string
    {
        return 'spacer';
    }

    public static function label(): string
    {
        return 'Spacer';
    }

    public static function icon(): ?string
    {
        return 'heroicon-o-arrows-up-down';
    }

    public static function defaults(): array
    {
        return [
            'size' => 'md',
        ];
    }

    public static function schema(): array
    {
        return [];
    }

    public static function toHtml(array $data, string $childrenHtml = ''): string
    {
        $size = TokenMap::resolve('spacer', $data['size'] ?? 'md');
        if ($size === '') {
            $size = 'h-12';
        }

        return '<div class="'.Escaper::e($size).'" aria-hidden="true"></div>';
    }

    public static function isEmailSafe(): bool
    {
        return true;
    }
}
