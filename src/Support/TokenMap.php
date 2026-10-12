<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Support;

/**
 * Central mapping from design tokens to complete, literal Tailwind v4 utility classes.
 * Never concatenate class names; every value is a full, safelist-able string.
 */
final class TokenMap
{
    public const EXPORTER_VERSION = '1.0.0';

    /** @var array<string, string> */
    public const PADDING = [
        'none' => '',
        'xs' => 'p-2',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
        'xl' => 'p-12',
        '2xl' => 'p-16',
    ];

    /** @var array<string, string> */
    public const PADDING_Y = [
        'none' => '',
        'xs' => 'py-2',
        'sm' => 'py-4',
        'md' => 'py-6',
        'lg' => 'py-8',
        'xl' => 'py-12',
        '2xl' => 'py-16',
        '3xl' => 'py-24',
    ];

    /** @var array<string, string> */
    public const WIDTH = [
        'narrow' => 'max-w-2xl mx-auto',
        'default' => 'max-w-4xl mx-auto',
        'wide' => 'max-w-6xl mx-auto',
        'full' => 'max-w-full',
        'prose' => 'max-w-prose mx-auto',
    ];

    /** @var array<string, string> */
    public const BACKGROUND = [
        'none' => '',
        'white' => 'bg-white',
        'gray-50' => 'bg-gray-50',
        'gray-100' => 'bg-gray-100',
        'gray-900' => 'bg-gray-900',
        'primary' => 'bg-blue-600',
        'primary-soft' => 'bg-blue-50',
        'dark' => 'bg-slate-900',
    ];

    /** @var array<string, string> */
    public const TEXT_COLOR = [
        'default' => 'text-gray-900',
        'muted' => 'text-gray-600',
        'white' => 'text-white',
        'primary' => 'text-blue-600',
        'inherit' => '',
    ];

    /** @var array<string, string> */
    public const ALIGN = [
        'start' => 'text-left',
        'center' => 'text-center',
        'end' => 'text-right',
    ];

    /** @var array<string, string> */
    public const JUSTIFY = [
        'start' => 'justify-start',
        'center' => 'justify-center',
        'end' => 'justify-end',
        'between' => 'justify-between',
    ];

    /** @var array<string, string> */
    public const GAP = [
        'none' => 'gap-0',
        'xs' => 'gap-2',
        'sm' => 'gap-4',
        'md' => 'gap-6',
        'lg' => 'gap-8',
        'xl' => 'gap-12',
    ];

    /** @var array<string, string> */
    public const COLUMNS = [
        '1' => 'grid grid-cols-1',
        '2' => 'grid grid-cols-1 md:grid-cols-2',
        '3' => 'grid grid-cols-1 md:grid-cols-3',
        '4' => 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
        '1-2' => 'grid grid-cols-1 md:grid-cols-3 [&>*:first-child]:md:col-span-1 [&>*:last-child]:md:col-span-2',
        '2-1' => 'grid grid-cols-1 md:grid-cols-3 [&>*:first-child]:md:col-span-2 [&>*:last-child]:md:col-span-1',
    ];

    /** @var array<string, array{tag: string, classes: string}> */
    public const HEADING = [
        'h1' => ['tag' => 'h1', 'classes' => 'text-4xl md:text-5xl font-bold tracking-tight'],
        'h2' => ['tag' => 'h2', 'classes' => 'text-3xl md:text-4xl font-bold tracking-tight'],
        'h3' => ['tag' => 'h3', 'classes' => 'text-2xl md:text-3xl font-semibold'],
        'h4' => ['tag' => 'h4', 'classes' => 'text-xl md:text-2xl font-semibold'],
        'h5' => ['tag' => 'h5', 'classes' => 'text-lg font-medium'],
        'h6' => ['tag' => 'h6', 'classes' => 'text-base font-medium'],
    ];

    /** @var array<string, string> */
    public const BUTTON = [
        'primary' => 'inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
        'secondary' => 'inline-flex items-center justify-center rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2',
        'outline' => 'inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
        'ghost' => 'inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2',
    ];

    /** @var array<string, string> */
    public const SPACER = [
        'xs' => 'h-4',
        'sm' => 'h-8',
        'md' => 'h-12',
        'lg' => 'h-16',
        'xl' => 'h-24',
        '2xl' => 'h-32',
    ];

    /** @var array<string, string> */
    public const DIVIDER = [
        'solid' => 'border-t border-gray-200',
        'dashed' => 'border-t border-dashed border-gray-300',
        'thick' => 'border-t-2 border-gray-300',
    ];

    public static function resolve(string $group, ?string $token): string
    {
        if ($token === null || $token === '') {
            return '';
        }

        $map = match ($group) {
            'padding' => self::PADDING,
            'padding_y' => self::PADDING_Y,
            'width' => self::WIDTH,
            'background' => self::BACKGROUND,
            'text_color' => self::TEXT_COLOR,
            'align' => self::ALIGN,
            'justify' => self::JUSTIFY,
            'gap' => self::GAP,
            'columns' => self::COLUMNS,
            'button' => self::BUTTON,
            'spacer' => self::SPACER,
            'divider' => self::DIVIDER,
            default => [],
        };

        return $map[$token] ?? '';
    }

    /** @param array<string, string|null> $tokens */
    public static function classes(array $tokens): string
    {
        $parts = [];
        foreach ($tokens as $group => $token) {
            $class = self::resolve($group, $token);
            if ($class !== '') {
                $parts[] = $class;
            }
        }

        return implode(' ', $parts);
    }

    /** @return list<string> */
    public static function allClasses(): array
    {
        $classes = [];

        foreach ([
            self::PADDING,
            self::PADDING_Y,
            self::WIDTH,
            self::BACKGROUND,
            self::TEXT_COLOR,
            self::ALIGN,
            self::JUSTIFY,
            self::GAP,
            self::COLUMNS,
            self::BUTTON,
            self::SPACER,
            self::DIVIDER,
        ] as $map) {
            foreach ($map as $value) {
                if ($value === '') {
                    continue;
                }
                foreach (preg_split('/\s+/', $value) as $part) {
                    if ($part !== '') {
                        $classes[] = $part;
                    }
                }
            }
        }

        foreach (self::HEADING as $def) {
            foreach (preg_split('/\s+/', $def['classes']) as $part) {
                if ($part !== '') {
                    $classes[] = $part;
                }
            }
        }

        $extra = [
            'w-full', 'h-auto', 'object-cover', 'rounded-lg', 'overflow-hidden',
            'aspect-video', 'block', 'inline-block', 'mx-auto', 'my-4',
            'prose', 'prose-lg', 'max-w-none', 'sr-only',
        ];

        return array_values(array_unique(array_merge($classes, $extra)));
    }
}
