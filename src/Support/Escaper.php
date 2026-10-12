<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Support;

final class Escaper
{
    public static function e(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', true);
    }
}
