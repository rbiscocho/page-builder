<x-filament-panels::page class="fi-page-builder-design !p-0">
<div class="flex h-[calc(100vh-4rem)] flex-col bg-gray-950 text-gray-100">
  <div class="flex items-center justify-between border-b border-gray-800 px-4 py-2">
    <div class="flex items-center gap-2">
      <span class="font-medium text-white">{{ $this->record->title }}</span>
      <span class="text-xs text-gray-400">{{ $status ?? 'draft' }}</span>
      @if ($isDirty)
        <span class="text-xs text-amber-400">Unsaved</span>
      @endif
    </div>
    <div class="flex gap-1">
      <button type="button" wire:click="undo" class="rounded p-2 text-gray-400 hover:bg-gray-800">Undo</button>
      <button type="button" wire:click="redo" class="rounded p-2 text-gray-400 hover:bg-gray-800">Redo</button>
      @foreach (['desktop', 'tablet', 'mobile'] as $w)
        <button type="button" wire:click="setPreviewWidth('{{ $w }}')"
          @class(['rounded px-2 py-1 text-xs', 'bg-primary-600 text-white' => $previewWidth === $w, 'text-gray-400' => $previewWidth !== $w])
        >{{ $w }}</button>
      @endforeach
      <button type="button" wire:click="setActiveTab('canvas')" class="rounded px-2 py-1 text-xs">Canvas</button>
      <button type="button" wire:click="setActiveTab('code')" class="rounded px-2 py-1 text-xs">Code</button>
    </div>
  </div>
  <div class="flex min-h-0 flex-1">
    <aside class="w-56 overflow-y-auto border-r border-gray-800 p-2">
      <div class="mb-2 text-xs uppercase text-gray-500">Blocks</div>
      @foreach ($palette as $item)
        <button type="button" wire:click="addBlock('{{ $item['type'] }}')"
          class="mb-1 flex w-full items-center gap-2 rounded border border-gray-800 px-2 py-2 text-left text-sm hover:bg-gray-800">
          <span>{{ $item['label'] }}</span>
        </button>
      @endforeach
    </aside>
    <main class="flex-1 overflow-auto p-4">
      @if ($activeTab === 'code')
        <pre class="rounded bg-gray-950 p-4 text-xs text-emerald-300">{{ $liveHtml }}</pre>
      @else
        <div class="mx-auto rounded border border-gray-700 bg-white p-4 text-gray-900" style="max-width: {{ $previewMaxWidth }}">
          @forelse ($design as $block)
            @include('filament-page-builder::filament.pages.partials.block-chip', ['block' => $block])
          @empty
            <p class="py-16 text-center text-gray-400">Empty canvas — add blocks from the left</p>
          @endforelse
          @if ($liveHtml !== '')
            <div class="mt-4 border-t border-dashed border-gray-200 pt-4">{!! $liveHtml !!}</div>
          @endif
        </div>
      @endif
    </main>
    <aside class="w-72 overflow-y-auto border-l border-gray-800 p-3">
      <div class="mb-2 text-xs uppercase text-gray-500">Style</div>
      @if ($selected)
        @php $data = $selected['data'] ?? []; @endphp
        <div class="mb-2 flex justify-between">
          <span class="text-sm font-medium">{{ $selected['type'] ?? '' }}</span>
          <div class="flex gap-1">
            <button type="button" wire:click="moveSelected('up')" class="text-xs text-gray-400">Up</button>
            <button type="button" wire:click="moveSelected('down')" class="text-xs text-gray-400">Down</button>
            <button type="button" wire:click="duplicateSelected" class="text-xs text-gray-400">Dup</button>
            <button type="button" wire:click="deleteSelected" wire:confirm="Delete?" class="text-xs text-red-400">Del</button>
          </div>
        </div>
        @if (($selected['type'] ?? '') === 'heading')
          <label class="block text-xs text-gray-400">Text
            <input type="text" value="{{ $data['text'] ?? '' }}" wire:change="updateField('text', $event.target.value)" class="mt-1 w-full rounded border-gray-700 bg-gray-950 text-sm text-white" />
          </label>
          @include('filament-page-builder::filament.pages.partials.token-select', ['label' => 'Level', 'field' => 'level', 'options' => $tokenOptions['level'], 'value' => $data['level'] ?? 'h2'])
        @elseif (($selected['type'] ?? '') === 'text')
          <label class="block text-xs text-gray-400">Content
            <textarea rows="4" wire:change="updateField('content', $event.target.value)" class="mt-1 w-full rounded border-gray-700 bg-gray-950 text-sm text-white">{{ $data['content'] ?? '' }}</textarea>
          </label>
        @elseif (($selected['type'] ?? '') === 'button')
          <label class="block text-xs text-gray-400">Label
            <input type="text" value="{{ $data['label'] ?? '' }}" wire:change="updateField('label', $event.target.value)" class="mt-1 w-full rounded border-gray-700 bg-gray-950 text-sm text-white" />
          </label>
          <label class="block text-xs text-gray-400">URL
            <input type="text" value="{{ $data['href'] ?? '' }}" wire:change="updateField('href', $event.target.value)" class="mt-1 w-full rounded border-gray-700 bg-gray-950 text-sm text-white" />
          </label>
          @include('filament-page-builder::filament.pages.partials.token-select', ['label' => 'Variant', 'field' => 'variant', 'options' => $tokenOptions['button'], 'value' => $data['variant'] ?? 'primary'])
        @elseif (($selected['type'] ?? '') === 'image')
          <label class="block text-xs text-gray-400">Src
            <input type="text" value="{{ $data['src'] ?? '' }}" wire:change="updateField('src', $event.target.value)" class="mt-1 w-full rounded border-gray-700 bg-gray-950 text-sm text-white" />
          </label>
          <label class="block text-xs text-gray-400">Alt
            <input type="text" value="{{ $data['alt'] ?? '' }}" wire:change="updateField('alt', $event.target.value)" class="mt-1 w-full rounded border-gray-700 bg-gray-950 text-sm text-white" />
          </label>
        @elseif (($selected['type'] ?? '') === 'embed')
          <label class="block text-xs text-gray-400">URL
            <input type="text" value="{{ $data['url'] ?? '' }}" wire:change="updateField('url', $event.target.value)" class="mt-1 w-full rounded border-gray-700 bg-gray-950 text-sm text-white" />
          </label>
        @elseif (($selected['type'] ?? '') === 'spacer')
          @include('filament-page-builder::filament.pages.partials.token-select', ['label' => 'Size', 'field' => 'size', 'options' => $tokenOptions['spacer'], 'value' => $data['size'] ?? 'md'])
        @elseif (($selected['type'] ?? '') === 'divider')
          @include('filament-page-builder::filament.pages.partials.token-select', ['label' => 'Style', 'field' => 'style', 'options' => $tokenOptions['divider'], 'value' => $data['style'] ?? 'solid'])
        @elseif (($selected['type'] ?? '') === 'section')
          @include('filament-page-builder::filament.pages.partials.token-select', ['label' => 'Columns', 'field' => 'columns', 'options' => $tokenOptions['columns'], 'value' => $data['columns'] ?? '1'])
          @include('filament-page-builder::filament.pages.partials.token-select', ['label' => 'Gap', 'field' => 'gap', 'options' => $tokenOptions['gap'], 'value' => $data['gap'] ?? 'md'])
        @endif
        <div class="mt-3 space-y-2 border-t border-gray-800 pt-3">
          <p class="text-[11px] uppercase text-gray-500">Tokens</p>
          @include('filament-page-builder::filament.pages.partials.token-chips', ['label' => 'Padding Y', 'field' => 'padding_y', 'options' => $tokenOptions['padding_y'], 'value' => $data['padding_y'] ?? 'none'])
          @include('filament-page-builder::filament.pages.partials.token-chips', ['label' => 'Width', 'field' => 'width', 'options' => $tokenOptions['width'], 'value' => $data['width'] ?? 'default'])
          @include('filament-page-builder::filament.pages.partials.token-chips', ['label' => 'Background', 'field' => 'background', 'options' => $tokenOptions['background'], 'value' => $data['background'] ?? 'none'])
          @include('filament-page-builder::filament.pages.partials.token-chips', ['label' => 'Align', 'field' => 'align', 'options' => $tokenOptions['align'], 'value' => $data['align'] ?? 'start'])
        </div>
      @else
        <p class="text-sm text-gray-500">Select a block to edit.</p>
      @endif
    </aside>
  </div>
</div>
</x-filament-panels::page>
