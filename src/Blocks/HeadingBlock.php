<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Blocks;

use PageBuilder\FilamentPageBuilder\Contracts\Block;
use PageBuilder\FilamentPageBuilder\Support\Escaper;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

final class HeadingBlock implements Block
{
    public static function type(): string
    {
        return 'heading';
    }

    public static function label(): string
    {
        return 'Heading';
    }

    public static function icon(): ?string
    {
        return 'heroicon-o-hashtag';
    }

    public static function defaults(): array
    {
        return [
            'text' => 'Heading',
            'level' => 'h2',
            'align' => 'start',
            'text_color' => 'default',
            'padding_y' => 'none',
        ];
    }

    public static function schema(): array
    {
        return [];
    }

    public static function toHtml(array $data, string $childrenHtml = ''): string
    {
        $level = $data['level'] ?? 'h2';
        $def = TokenMap::HEADING[$level] ?? TokenMap::HEADING['h2'];
        $tag = $def['tag'];
        $sizeClasses = $def['classes'];

        $align = TokenMap::resolve('align', $data['align'] ?? 'start');
        $color = TokenMap::resolve('text_color', $data['text_color'] ?? 'default');
        $paddingY = TokenMap::resolve('padding_y', $data['padding_y'] ?? 'none');

        $classes = trim(implode(' ', array_filter([$sizeClasses, $align, $color, $paddingY])));
        $text = Escaper::e((string) ($data['text'] ?? ''));

        return "<{$tag} class=\"".Escaper::e($classes)."\">{$text}</{$tag}>";
    }

    public static function isEmailSafe(): bool
    {
        return true;
    }
}
