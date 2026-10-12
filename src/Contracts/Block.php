<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Contracts;

interface Block
{
    public static function type(): string;
    public static function label(): string;
    public static function icon(): ?string;
    /** @return array<string, mixed> */
    public static function defaults(): array;
    /** @return array<int, \Filament\Forms\Components\Component> */
    public static function schema(): array;
    /** @param array<string, mixed> $data */
    public static function toHtml(array $data, string $childrenHtml = ''): string;
    public static function isEmailSafe(): bool;
}
