<?php

declare(strict_types=1);

use PageBuilder\FilamentPageBuilder\Blocks\ButtonBlock;
use PageBuilder\FilamentPageBuilder\Blocks\DividerBlock;
use PageBuilder\FilamentPageBuilder\Blocks\EmbedBlock;
use PageBuilder\FilamentPageBuilder\Blocks\HeadingBlock;
use PageBuilder\FilamentPageBuilder\Blocks\ImageBlock;
use PageBuilder\FilamentPageBuilder\Blocks\SectionBlock;
use PageBuilder\FilamentPageBuilder\Blocks\SpacerBlock;
use PageBuilder\FilamentPageBuilder\Blocks\TextBlock;
use PageBuilder\FilamentPageBuilder\Support\BlockRegistry;
use PageBuilder\FilamentPageBuilder\Support\HtmlExporter;

function makeExporter(): HtmlExporter
{
    $registry = new BlockRegistry;
    $registry->register([
        SectionBlock::class,
        HeadingBlock::class,
        TextBlock::class,
        ImageBlock::class,
        ButtonBlock::class,
        EmbedBlock::class,
        SpacerBlock::class,
        DividerBlock::class,
    ]);

    return new HtmlExporter($registry);
}

it('exports a simple heading', function () {
    $html = makeExporter()->export([
        [
            'id' => '1',
            'type' => 'heading',
            'data' => ['text' => 'Hello World', 'level' => 'h1'],
        ],
    ]);

    expect($html)->toContain('<h1');
    expect($html)->toContain('Hello World');
    expect($html)->toContain('text-4xl');
    expect($html)->not->toContain('style=');
});

it('escapes user text', function () {
    $html = makeExporter()->export([
        [
            'id' => '1',
            'type' => 'heading',
            'data' => ['text' => '<script>alert(1)</script>', 'level' => 'h2'],
        ],
    ]);

    expect($html)->not->toContain('<script>');
    expect($html)->toContain('&lt;script&gt;');
});

it('skips unknown block types', function () {
    $html = makeExporter()->export([
        ['id' => '1', 'type' => 'heading', 'data' => ['text' => 'Keep']],
        ['id' => '2', 'type' => 'retired-widget', 'data' => ['foo' => 'bar']],
        ['id' => '3', 'type' => 'text', 'data' => ['content' => 'Also keep']],
    ]);

    expect($html)->toContain('Keep');
    expect($html)->toContain('Also keep');
    expect($html)->not->toContain('retired');
});

it('renders nested section with children', function () {
    $html = makeExporter()->export([
        [
            'id' => 'sec',
            'type' => 'section',
            'data' => ['columns' => '2', 'gap' => 'md'],
            'children' => [
                ['id' => 'h1', 'type' => 'heading', 'data' => ['text' => 'Title', 'level' => 'h2']],
                ['id' => 't1', 'type' => 'text', 'data' => ['content' => 'Body']],
            ],
        ],
    ]);

    expect($html)->toContain('<section');
    expect($html)->toContain('Title');
    expect($html)->toContain('Body');
});

it('blocks javascript hrefs', function () {
    $html = makeExporter()->export([
        [
            'id' => '1',
            'type' => 'button',
            'data' => ['label' => 'Bad', 'href' => 'javascript:alert(1)'],
        ],
    ]);

    expect($html)->toContain('href="#"');
    expect($html)->not->toContain('javascript:');
});

it('adds data-link-type for internal links', function () {
    $html = makeExporter()->export([
        [
            'id' => '1',
            'type' => 'button',
            'data' => ['label' => 'Go', 'href' => '/about', 'link_type' => 'internal'],
        ],
    ]);

    expect($html)->toContain('data-link-type="internal"');
});

it('rejects non-allowlisted embed hosts', function () {
    $html = makeExporter()->export([
        [
            'id' => '1',
            'type' => 'embed',
            'data' => ['url' => 'https://evil.example.com/embed'],
        ],
    ]);

    expect($html)->toBe('');
});

it('accepts youtube embeds and normalises youtu.be', function () {
    $html = makeExporter()->export([
        [
            'id' => '1',
            'type' => 'embed',
            'data' => ['url' => 'https://youtu.be/dQw4w9WgXcQ'],
        ],
    ]);

    expect($html)->toContain('youtube.com/embed/dQw4w9WgXcQ');
    expect($html)->toContain('<iframe');
});

it('produces absolute image URLs', function () {
    $html = makeExporter()->export([
        [
            'id' => '1',
            'type' => 'image',
            'data' => ['src' => 'https://cdn.example.com/pic.jpg', 'alt' => 'Pic'],
        ],
    ]);

    expect($html)->toContain('src="https://cdn.example.com/pic.jpg"');
    expect($html)->toContain('alt="Pic"');
    expect($html)->not->toContain('style=');
});

it('emits no style attributes anywhere', function () {
    $html = makeExporter()->export([
        [
            'id' => 'sec',
            'type' => 'section',
            'data' => ['columns' => '3', 'background' => 'gray-50', 'padding_y' => 'lg'],
            'children' => [
                ['id' => '1', 'type' => 'heading', 'data' => ['text' => 'T', 'level' => 'h2']],
                ['id' => '2', 'type' => 'text', 'data' => ['content' => "Line 1\nLine 2"]],
                ['id' => '3', 'type' => 'spacer', 'data' => ['size' => 'lg']],
                ['id' => '4', 'type' => 'divider', 'data' => []],
                ['id' => '5', 'type' => 'button', 'data' => ['label' => 'Go', 'href' => '/x']],
            ],
        ],
    ]);

    expect($html)->not->toMatch('/\sstyle\s=/');
});
