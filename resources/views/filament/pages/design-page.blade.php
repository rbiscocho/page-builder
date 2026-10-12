<x-filament-panels::page>
<style>
/* Scoped canvas UI — does not depend on host Tailwind purge */
.pb-root { display:flex; flex-direction:column; height:calc(100vh - 9.5rem); margin:-1rem; background:#0b0f19; color:#e5e7eb; border-radius:0.75rem; overflow:hidden; border:1px solid #1f2937; }
.pb-toolbar { display:flex; align-items:center; justify-content:space-between; gap:0.75rem; padding:0.5rem 1rem; background:#111827; border-bottom:1px solid #1f2937; flex-shrink:0; }
.pb-toolbar .meta { display:flex; align-items:center; gap:0.5rem; font-size:0.875rem; }
.pb-toolbar .meta .title { font-weight:600; color:#fff; }
.pb-badge { font-size:0.7rem; padding:0.15rem 0.5rem; border-radius:999px; background:#422006; color:#fbbf24; }
.pb-badge.pub { background:#064e3b; color:#6ee7b7; }
.pb-dirty { font-size:0.75rem; color:#fbbf24; }
.pb-tools { display:flex; align-items:center; gap:0.25rem; flex-wrap:wrap; }
.pb-btn { appearance:none; border:0; background:transparent; color:#9ca3af; padding:0.35rem 0.6rem; border-radius:0.4rem; font-size:0.75rem; cursor:pointer; }
.pb-btn:hover { background:#1f2937; color:#fff; }
.pb-btn.active { background:#2563eb; color:#fff; }
.pb-sep { width:1px; height:1.25rem; background:#374151; margin:0 0.25rem; }
.pb-body { display:flex; min-height:0; flex:1; }
.pb-palette { width:13rem; flex-shrink:0; border-right:1px solid #1f2937; background:#111827; display:flex; flex-direction:column; }
.pb-palette h3, .pb-inspector h3 { margin:0; padding:0.6rem 0.75rem; font-size:0.65rem; letter-spacing:0.06em; text-transform:uppercase; color:#6b7280; border-bottom:1px solid #1f2937; }
.pb-palette-list { padding:0.5rem; overflow:auto; flex:1; display:flex; flex-direction:column; gap:0.35rem; }
.pb-palette-item { display:flex; align-items:center; gap:0.5rem; width:100%; text-align:left; padding:0.5rem 0.65rem; border-radius:0.5rem; border:1px solid #1f2937; background:#0b0f19; color:#e5e7eb; font-size:0.8125rem; cursor:pointer; }
.pb-palette-item:hover { border-color:#3b82f6; background:#1e293b; }
.pb-canvas-wrap { flex:1; min-width:0; overflow:auto; padding:1.25rem; background:#0f172a; display:flex; justify-content:center; align-items:flex-start; }
.pb-frame { width:100%; background:#fff; color:#111827; border-radius:0.75rem; box-shadow:0 25px 50px -12px rgb(0 0 0 / 0.5); border:1px solid #334155; overflow:hidden; min-height:28rem; transition:max-width .2s ease; }
.pb-frame-bar { padding:0.35rem 0.75rem; font-size:0.65rem; letter-spacing:0.08em; text-transform:uppercase; color:#94a3b8; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
.pb-frame-body { padding:1rem; display:flex; flex-direction:column; gap:0.5rem; }
.pb-empty { text-align:center; padding:4rem 1rem; color:#94a3b8; font-size:0.875rem; }
.pb-chip { display:flex; align-items:center; gap:0.5rem; width:100%; text-align:left; padding:0.55rem 0.75rem; border-radius:0.5rem; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.8125rem; cursor:pointer; transition:border-color .15s, box-shadow .15s; }
.pb-chip:hover { border-color:#94a3b8; }
.pb-chip.is-selected { border-color:#3b82f6; box-shadow:0 0 0 2px rgb(59 130 246 / 0.25); background:#eff6ff; }
.pb-chip-type { font-size:0.6rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; color:#64748b; background:#f1f5f9; padding:0.15rem 0.4rem; border-radius:0.25rem; flex-shrink:0; }
.pb-chip-label { flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.pb-render { margin-top:0.75rem; padding-top:0.75rem; border-top:1px dashed #cbd5e1; }
.pb-render-label { font-size:0.65rem; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; color:#94a3b8; margin-bottom:0.5rem; }
.pb-code { width:100%; margin:0; padding:1rem; background:#020617; color:#6ee7b7; font-size:0.75rem; line-height:1.5; border-radius:0.5rem; overflow:auto; white-space:pre-wrap; word-break:break-word; }
.pb-inspector { width:17rem; flex-shrink:0; border-left:1px solid #1f2937; background:#111827; display:flex; flex-direction:column; overflow:hidden; }
.pb-inspector-body { padding:0.75rem; overflow:auto; flex:1; font-size:0.8125rem; }
.pb-inspector-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem; }
.pb-inspector-head strong { color:#fff; text-transform:capitalize; }
.pb-icon-row { display:flex; gap:0.15rem; }
.pb-field { display:block; margin-bottom:0.65rem; color:#9ca3af; font-size:0.7rem; }
.pb-field input, .pb-field textarea, .pb-field select {
  display:block; width:100%; margin-top:0.25rem; padding:0.4rem 0.5rem; border-radius:0.4rem;
  border:1px solid #374151; background:#0b0f19; color:#f3f4f6; font-size:0.8125rem;
}
.pb-field textarea { min-height:5rem; resize:vertical; }
.pb-tokens { border-top:1px solid #1f2937; padding-top:0.75rem; margin-top:0.5rem; }
.pb-tokens-title { font-size:0.65rem; letter-spacing:0.06em; text-transform:uppercase; color:#6b7280; margin-bottom:0.5rem; }
.pb-chip-row { display:flex; flex-wrap:wrap; gap:0.25rem; margin-bottom:0.65rem; }
.pb-chip-row span { display:block; width:100%; font-size:0.7rem; color:#9ca3af; margin-bottom:0.2rem; }
.pb-tok { appearance:none; border:0; background:#1f2937; color:#d1d5db; padding:0.25rem 0.45rem; border-radius:0.3rem; font-size:0.7rem; cursor:pointer; }
.pb-tok:hover { background:#374151; color:#fff; }
.pb-tok.on { background:#2563eb; color:#fff; }
.pb-hint { font-size:0.7rem; color:#6b7280; margin-top:0.75rem; line-height:1.4; }
@media (max-width: 900px) {
  .pb-palette { width:10rem; }
  .pb-inspector { width:14rem; }
}
</style>

<div class="pb-root" wire:key="pb-canvas-root">
  <div class="pb-toolbar">
    <div class="meta">
      <span class="title">{{ $this->record->title }}</span>
      <span @class(['pb-badge', 'pub' => ($status ?? '') === 'published'])>{{ $status ?? 'draft' }}</span>
      @if ($isDirty)
        <span class="pb-dirty">Unsaved</span>
      @endif
    </div>
    <div class="pb-tools">
      <button type="button" class="pb-btn" wire:click="undo" title="Undo">Undo</button>
      <button type="button" class="pb-btn" wire:click="redo" title="Redo">Redo</button>
      <span class="pb-sep"></span>
      @foreach (['desktop' => 'Desktop', 'tablet' => 'Tablet', 'mobile' => 'Mobile'] as $key => $label)
        <button type="button" wire:click="setPreviewWidth('{{ $key }}')" @class(['pb-btn', 'active' => $previewWidth === $key])>{{ $label }}</button>
      @endforeach
      <span class="pb-sep"></span>
      <button type="button" wire:click="setActiveTab('canvas')" @class(['pb-btn', 'active' => $activeTab === 'canvas'])>Canvas</button>
      <button type="button" wire:click="setActiveTab('code')" @class(['pb-btn', 'active' => $activeTab === 'code'])>Code</button>
    </div>
  </div>

  <div class="pb-body">
    <aside class="pb-palette">
      <h3>Blocks</h3>
      <div class="pb-palette-list">
        @foreach ($palette as $item)
          <button type="button" class="pb-palette-item" wire:click="addBlock('{{ $item['type'] }}')">
            {{ $item['label'] }}
          </button>
        @endforeach
      </div>
    </aside>

    <main class="pb-canvas-wrap">
      @if ($activeTab === 'code')
        <pre class="pb-code">{{ $liveHtml !== '' ? $liveHtml : '<!-- empty — add blocks and save -->' }}</pre>
      @else
        <div class="pb-frame" style="max-width: {{ $previewMaxWidth }};">
          <div class="pb-frame-bar">Preview · {{ $previewWidth }}</div>
          <div class="pb-frame-body">
            @forelse ($design as $block)
              @php
                $isSelected = ($selectedId ?? null) === ($block['id'] ?? null);
                $type = $block['type'] ?? 'unknown';
                $data = $block['data'] ?? [];
                $label = match ($type) {
                  'heading' => $data['text'] ?? 'Heading',
                  'text' => \Illuminate\Support\Str::limit($data['content'] ?? 'Text', 48),
                  'button' => $data['label'] ?? 'Button',
                  'image' => ($data['alt'] ?: null) ?: ($data['src'] ?? 'Image'),
                  'embed' => $data['url'] ?? 'Embed',
                  'spacer' => 'Spacer ('.($data['size'] ?? 'md').')',
                  'divider' => 'Divider',
                  'section' => 'Section · '.($data['columns'] ?? '1').' col',
                  default => $type,
                };
              @endphp
              <button
                type="button"
                class="pb-chip {{ $isSelected ? 'is-selected' : '' }}"
                wire:click="selectBlock('{{ $block['id'] }}')"
              >
                <span class="pb-chip-type">{{ $type }}</span>
                <span class="pb-chip-label">{{ $label }}</span>
              </button>
            @empty
              <div class="pb-empty">Empty canvas — click a block on the left to add it</div>
            @endforelse

            @if ($liveHtml !== '')
              <div class="pb-render">
                <div class="pb-render-label">Rendered output</div>
                <div>{!! $liveHtml !!}</div>
              </div>
            @endif
          </div>
        </div>
      @endif
    </main>

    <aside class="pb-inspector">
      <h3>Style</h3>
      <div class="pb-inspector-body">
        @if ($selected)
          @php $data = $selected['data'] ?? []; @endphp
          <div class="pb-inspector-head">
            <strong>{{ $selected['type'] ?? 'block' }}</strong>
            <div class="pb-icon-row">
              <button type="button" class="pb-btn" wire:click="moveSelected('up')" title="Move up">↑</button>
              <button type="button" class="pb-btn" wire:click="moveSelected('down')" title="Move down">↓</button>
              <button type="button" class="pb-btn" wire:click="duplicateSelected" title="Duplicate">Dup</button>
              <button type="button" class="pb-btn" style="color:#f87171" wire:click="deleteSelected" wire:confirm="Delete this block?" title="Delete">Del</button>
            </div>
          </div>

          @if (($selected['type'] ?? '') === 'heading')
            <label class="pb-field">Text
              <input type="text" value="{{ $data['text'] ?? '' }}" wire:change="updateField('text', $event.target.value)" />
            </label>
            <label class="pb-field">Level
              <select wire:change="updateField('level', $event.target.value)">
                @foreach ($tokenOptions['level'] as $opt)
                  <option value="{{ $opt }}" @selected(($data['level'] ?? 'h2') === $opt)>{{ $opt }}</option>
                @endforeach
              </select>
            </label>
          @elseif (($selected['type'] ?? '') === 'text')
            <label class="pb-field">Content
              <textarea wire:change="updateField('content', $event.target.value)">{{ $data['content'] ?? '' }}</textarea>
            </label>
          @elseif (($selected['type'] ?? '') === 'button')
            <label class="pb-field">Label
              <input type="text" value="{{ $data['label'] ?? '' }}" wire:change="updateField('label', $event.target.value)" />
            </label>
            <label class="pb-field">URL
              <input type="text" value="{{ $data['href'] ?? '' }}" wire:change="updateField('href', $event.target.value)" />
            </label>
            <label class="pb-field">Variant
              <select wire:change="updateField('variant', $event.target.value)">
                @foreach ($tokenOptions['button'] as $opt)
                  <option value="{{ $opt }}" @selected(($data['variant'] ?? 'primary') === $opt)>{{ $opt }}</option>
                @endforeach
              </select>
            </label>
          @elseif (($selected['type'] ?? '') === 'image')
            <label class="pb-field">Src
              <input type="text" value="{{ $data['src'] ?? '' }}" wire:change="updateField('src', $event.target.value)" />
            </label>
            <label class="pb-field">Alt
              <input type="text" value="{{ $data['alt'] ?? '' }}" wire:change="updateField('alt', $event.target.value)" />
            </label>
          @elseif (($selected['type'] ?? '') === 'embed')
            <label class="pb-field">URL
              <input type="text" value="{{ $data['url'] ?? '' }}" wire:change="updateField('url', $event.target.value)" />
            </label>
          @elseif (($selected['type'] ?? '') === 'spacer')
            <label class="pb-field">Size
              <select wire:change="updateField('size', $event.target.value)">
                @foreach ($tokenOptions['spacer'] as $opt)
                  <option value="{{ $opt }}" @selected(($data['size'] ?? 'md') === $opt)>{{ $opt }}</option>
                @endforeach
              </select>
            </label>
          @elseif (($selected['type'] ?? '') === 'divider')
            <label class="pb-field">Style
              <select wire:change="updateField('style', $event.target.value)">
                @foreach ($tokenOptions['divider'] as $opt)
                  <option value="{{ $opt }}" @selected(($data['style'] ?? 'solid') === $opt)>{{ $opt }}</option>
                @endforeach
              </select>
            </label>
          @elseif (($selected['type'] ?? '') === 'section')
            <label class="pb-field">Columns
              <select wire:change="updateField('columns', $event.target.value)">
                @foreach ($tokenOptions['columns'] as $opt)
                  <option value="{{ $opt }}" @selected(($data['columns'] ?? '1') === $opt)>{{ $opt }}</option>
                @endforeach
              </select>
            </label>
            <label class="pb-field">Gap
              <select wire:change="updateField('gap', $event.target.value)">
                @foreach ($tokenOptions['gap'] as $opt)
                  <option value="{{ $opt }}" @selected(($data['gap'] ?? 'md') === $opt)>{{ $opt }}</option>
                @endforeach
              </select>
            </label>
          @endif

          <div class="pb-tokens">
            <div class="pb-tokens-title">Design tokens</div>
            @foreach ([
              'padding_y' => 'Padding Y',
              'width' => 'Width',
              'background' => 'Background',
              'align' => 'Align',
            ] as $field => $label)
              <div class="pb-chip-row">
                <span>{{ $label }}</span>
                @foreach ($tokenOptions[$field] as $opt)
                  <button type="button" wire:click="updateField('{{ $field }}', '{{ $opt }}')" @class(['pb-tok', 'on' => ($data[$field] ?? null) === $opt])>{{ $opt }}</button>
                @endforeach
              </div>
            @endforeach
          </div>
          <p class="pb-hint">Tokens only — no free-form CSS. Output matches the React HTML.</p>
        @else
          <p class="pb-hint">Select a block on the canvas to edit content and tokens.</p>
        @endif
      </div>
    </aside>
  </div>
</div>
</x-filament-panels::page>
