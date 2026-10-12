<?php

declare(strict_types=1);

namespace PageBuilder\FilamentPageBuilder\Support;

use PageBuilder\FilamentPageBuilder\Contracts\Block;

/**
 * Walks a design tree and produces Tailwind HTML.
 * Unknown block types are skipped (never fatal).
 */
final class HtmlExporter
{
    public function __construct(
        private readonly BlockRegistry $registry,
    ) {}

    /**
     * @param  list<array<string, mixed>>  $design  Flat or nested block tree
     */
    public function export(array $design): string
    {
        $tree = $this->hydrateTree($design);
        $html = $this->renderNodes($tree);

        return trim($html);
    }

    /**
     * @param  list<array<string, mixed>>  $blocks
     * @return list<array<string, mixed>>
     */
    private function hydrateTree(array $blocks): array
    {
        $hasNested = false;
        foreach ($blocks as $b) {
            if (isset($b['children']) && is_array($b['children'])) {
                $hasNested = true;
                break;
            }
        }

        if ($hasNested || empty($blocks)) {
            return $blocks;
        }

        $byId = [];
        foreach ($blocks as $block) {
            $id = $block['id'] ?? uniqid('b_', true);
            $block['id'] = $id;
            $block['children'] = [];
            $byId[$id] = $block;
        }

        $roots = [];
        foreach ($byId as $id => $block) {
            $parentId = $block['parent'] ?? null;
            if ($parentId && isset($byId[$parentId])) {
                $slot = $block['slot'] ?? 0;
                $byId[$parentId]['children'][$slot][] = &$byId[$id];
            } else {
                $roots[] = &$byId[$id];
            }
        }

        foreach ($byId as &$block) {
            if (! empty($block['children'])) {
                ksort($block['children']);
                $flattened = [];
                foreach ($block['children'] as $slotChildren) {
                    usort($slotChildren, fn ($a, $b) => ($a['position'] ?? 0) <=> ($b['position'] ?? 0));
                    $flattened = array_merge($flattened, $slotChildren);
                }
                $block['children'] = $flattened;
            }
        }
        unset($block);

        usort($roots, fn ($a, $b) => ($a['position'] ?? 0) <=> ($b['position'] ?? 0));

        return $roots;
    }

    /**
     * @param  list<array<string, mixed>>  $nodes
     */
    private function renderNodes(array $nodes): string
    {
        $parts = [];
        foreach ($nodes as $node) {
            $html = $this->renderNode($node);
            if ($html !== '') {
                $parts[] = $html;
            }
        }

        return implode("\n", $parts);
    }

    /**
     * @param  array<string, mixed>  $node
     */
    private function renderNode(array $node): string
    {
        $type = $node['type'] ?? null;
        if (! is_string($type) || ! $this->registry->has($type)) {
            return '';
        }

        /** @var class-string<Block> $class */
        $class = $this->registry->get($type);

        $data = array_merge(
            $class::defaults(),
            $node['data'] ?? [],
            ['settings' => $node['settings'] ?? []]
        );

        if (isset($node['settings']) && is_array($node['settings'])) {
            $data = array_merge($data, $node['settings']);
        }

        $children = $node['children'] ?? [];
        $childrenHtml = is_array($children) ? $this->renderNodes($children) : '';

        return $class::toHtml($data, $childrenHtml);
    }

    public function version(): string
    {
        return TokenMap::EXPORTER_VERSION;
    }
}
