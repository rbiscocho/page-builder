<?php

declare(strict_types=1);

use PageBuilder\FilamentPageBuilder\Support\TokenMap;

it('resolves known padding tokens to complete class strings', function () {
    expect(TokenMap::resolve('padding', 'md'))->toBe('p-6');
    expect(TokenMap::resolve('padding', 'none'))->toBe('');
    expect(TokenMap::resolve('padding', 'unknown'))->toBe('');
});

it('never builds class names by concatenation', function () {
    $classes = TokenMap::allClasses();
    foreach ($classes as $class) {
        expect($class)->not->toBeEmpty();
        expect($class)->not->toContain('{{');
    }
});

it('includes responsive grid classes as complete literals', function () {
    $cols = TokenMap::resolve('columns', '3');
    expect($cols)->toContain('grid-cols-1');
    expect($cols)->toContain('md:grid-cols-3');
});

it('returns a unique sorted-safe list for the safelist command', function () {
    $all = TokenMap::allClasses();
    expect($all)->toBeArray();
    expect(count($all))->toBeGreaterThan(20);
    expect(count($all))->toBe(count(array_unique($all)));
});
