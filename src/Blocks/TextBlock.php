<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Blocks;

use PageBuilder\FilamentPageBuilder\Contracts\Block;
use PageBuilder\FilamentPageBuilder\Support\Escaper;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

final class TextBlock implements Block
{
    public static function type(): string
    {
        return 'text';
    }

    public static function label(): string
    {
        return 'Text';
    }

    public static function icon(): ?string
    {
        return 'heroicon-o-document-text';
    }

    public static function defaults(): array
    {
        return [
            'content' => '',
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
        $align = TokenMap::resolve('align', $data['align'] ?? 'start');
        $color = TokenMap::resolve('text_color', $data['text_color'] ?? 'default');
        $paddingY = TokenMap::resolve('padding_y', $data['padding_y'] ?? 'none');

        $classes = trim(implode(' ', array_filter(['prose max-w-none', $align, $color, $paddingY])));
        $content = Escaper::e((string) ($data['content'] ?? ''));
        $content = nl2br($content, false);

        return "<div class=\"".Escaper::e($classes)."\">{$content}</div>";
    }

    public static function isEmailSafe(): bool
    {
        return true;
    }
}
