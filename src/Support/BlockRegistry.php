<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Support;

use PageBuilder\FilamentPageBuilder\Contracts\Block;
use InvalidArgumentException;

final class BlockRegistry
{
    /** @var array<string, class-string<Block>> */
    private array $blocks = [];

    /**
     * @param  array<int, class-string<Block>>  $blockClasses
     */
    public function register(array $blockClasses): void
    {
        foreach ($blockClasses as $class) {
            if (! is_subclass_of($class, Block::class)) {
                throw new InvalidArgumentException("{$class} must implement ".Block::class);
            }
            $this->blocks[$class::type()] = $class;
        }
    }

    /**
     * @return class-string<Block>|null
     */
    public function get(string $type): ?string
    {
        return $this->blocks[$type] ?? null;
    }

    public function has(string $type): bool
    {
        return isset($this->blocks[$type]);
    }

    /**
     * @return array<string, class-string<Block>>
     */
    public function all(): array
    {
        return $this->blocks;
    }

    /**
     * Palette entries for the canvas UI.
     *
     * @return list<array{type: string, label: string, icon: string|null}>
     */
    public function palette(): array
    {
        $items = [];
        foreach ($this->blocks as $type => $class) {
            $items[] = [
                'type' => $type,
                'label' => $class::label(),
                'icon' => $class::icon(),
            ];
        }

        return $items;
    }
}
