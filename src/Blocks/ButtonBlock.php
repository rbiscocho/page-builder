<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Blocks;

use PageBuilder\FilamentPageBuilder\Contracts\Block;
use PageBuilder\FilamentPageBuilder\Support\Escaper;
use PageBuilder\FilamentPageBuilder\Support\TokenMap;

final class ButtonBlock implements Block
{
    public static function type(): string
    {
        return 'button';
    }

    public static function label(): string
    {
        return 'Button';
    }

    public static function icon(): ?string
    {
        return 'heroicon-o-cursor-arrow-rays';
    }

    public static function defaults(): array
    {
        return [
            'label' => 'Click me',
            'href' => '#',
            'variant' => 'primary',
            'align' => 'start',
            'link_type' => 'external',
            'padding_y' => 'none',
        ];
    }

    public static function schema(): array
    {
        return [];
    }

    public static function toHtml(array $data, string $childrenHtml = ''): string
    {
        $label = Escaper::e((string) ($data['label'] ?? 'Click me'));
        $href = self::safeHref((string) ($data['href'] ?? '#'));
        $variant = TokenMap::resolve('button', $data['variant'] ?? 'primary');
        $align = TokenMap::resolve('align', $data['align'] ?? 'start');
        $paddingY = TokenMap::resolve('padding_y', $data['padding_y'] ?? 'none');
        $linkType = ($data['link_type'] ?? 'external') === 'internal' ? 'internal' : 'external';

        $wrapperClasses = trim(implode(' ', array_filter([$align, $paddingY])));
        $dataAttr = $linkType === 'internal' ? ' data-link-type="internal"' : '';

        return '<div class="'.Escaper::e($wrapperClasses).'">'
            .'<a href="'.Escaper::e($href).'" class="'.Escaper::e($variant).'"'.$dataAttr.'>'.$label.'</a>'
            .'</div>';
    }

    public static function isEmailSafe(): bool
    {
        return true;
    }

    private static function safeHref(string $href): string
    {
        $href = trim($href);
        if ($href === '') {
            return '#';
        }
        $lower = strtolower($href);
        if (str_starts_with($lower, 'javascript:') || str_starts_with($lower, 'data:')) {
            return '#';
        }

        return $href;
    }
}
