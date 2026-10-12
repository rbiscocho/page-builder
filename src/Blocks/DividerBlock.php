<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Blocks;

use PageBuilder\FilamentPageBuilder\Contracts\Block;
use PageBuilder\FilamentPageBuilder\Support\Escaper;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

final class DividerBlock implements Block
{
    public static function type(): string
    {
        return 'divider';
    }

    public static function label(): string
    {
        return 'Divider';
    }

    public static function icon(): ?string
    {
        return 'heroicon-o-minus';
    }

    public static function defaults(): array
    {
        return [
            'style' => 'solid',
            'padding_y' => 'md',
        ];
    }

    public static function schema(): array
    {
        return [];
    }

    public static function toHtml(array $data, string $childrenHtml = ''): string
    {
        $style = TokenMap::resolve('divider', $data['style'] ?? 'solid');
        $paddingY = TokenMap::resolve('padding_y', $data['padding_y'] ?? 'md');
        $classes = trim(implode(' ', array_filter([$style, $paddingY])));

        return '<hr class="'.Escaper::e($classes).'" />';
    }

    public static function isEmailSafe(): bool
    {
        return true;
    }
}
