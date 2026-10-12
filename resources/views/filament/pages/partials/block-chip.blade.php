@php
    $isSelected = ($selectedId ?? null) === ($block['id'] ?? null);
    $type = $block['type'] ?? 'unknown';
    $data = $block['data'] ?? [];
    $label = match ($type) {
        'heading' => $data['text'] ?? 'Heading',
        'text' => \Illuminate\Support\Str::limit($data['content'] ?? 'Text', 40),
        'button' => $data['label'] ?? 'Button',
        'image' => $data['alt'] ?: ($data['src'] ?? 'Image'),
        'embed' => $data['url'] ?? 'Embed',
        'spacer' => 'Spacer ('.($data['size'] ?? 'md').')',
        'divider' => 'Divider',
        'section' => 'Section · '.($data['columns'] ?? '1').' col',
        default => $type,
    };
@endphp

<button
    type="button"
    wire:click="selectBlock('{{ $block['id'] }}')"
    @class([
        'group flex w-full items-start gap-2 rounded-lg border px-3 py-2 text-left text-sm transition',
        'border-primary-500 bg-primary-50 ring-2 ring-primary-500/30' => $isSelected,
        'border-gray-200 bg-white hover:border-gray-300' => ! $isSelected,
    ])
>
    <span class="mt-0.5 rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-gray-500">
        {{ $type }}
    </span>
    <span class="min-w-0 flex-1 truncate text-gray-800">{{ $label }}</span>
</button>

@if ($type === 'section' && ! empty($block['children']))
    <div class="ml-4 space-y-1 border-l-2 border-gray-100 pl-2">
        @foreach ($block['children'] as $child)
            @include('filament-page-builder::filament.pages.partials.block-chip', ['block' => $child])
        @endforeach
    </div>
@endif
