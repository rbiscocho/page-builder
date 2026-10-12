<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Blocks;

use PageBuilder\FilamentPageBuilder\Contracts\Block;
use PageBuilder\FilamentPageBuilder\Support\Escaper;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

final class SectionBlock implements Block
{
    public static function type(): string
    {
        return 'section';
    }

    public static function label(): string
    {
        return 'Section';
    }

    public static function icon(): ?string
    {
        return 'heroicon-o-view-columns';
    }

    public static function defaults(): array
    {
        return [
            'columns' => '1',
            'padding_y' => 'md',
            'width' => 'default',
            'background' => 'none',
            'gap' => 'md',
        ];
    }

    public static function schema(): array
    {
        return [];
    }

    public static function toHtml(array $data, string $childrenHtml = ''): string
    {
        $columns = TokenMap::resolve('columns', $data['columns'] ?? '1');
        $paddingY = TokenMap::resolve('padding_y', $data['padding_y'] ?? 'md');
        $width = TokenMap::resolve('width', $data['width'] ?? 'default');
        $background = TokenMap::resolve('background', $data['background'] ?? 'none');
        $gap = TokenMap::resolve('gap', $data['gap'] ?? 'md');

        $outerClasses = trim(implode(' ', array_filter([$paddingY, $background])));
        $innerClasses = trim(implode(' ', array_filter([$width, $columns, $gap])));

        $outerAttr = $outerClasses !== '' ? ' class="'.Escaper::e($outerClasses).'"' : '';
        $innerAttr = $innerClasses !== '' ? ' class="'.Escaper::e($innerClasses).'"' : '';

        return "<section{$outerAttr}>\n  <div{$innerAttr}>\n{$childrenHtml}\n  </div>\n</section>";
    }

    public static function isEmailSafe(): bool
    {
        return true;
    }
}
